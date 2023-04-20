<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Apache License Version 2.0
 */
namespace Smile\Map\Model;

use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smile\Map\Api\Data\AddressInterface;

/**
 * Address formatter tool.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class AddressFormatter
{
    /**
     * @var string
     */
    const FORMAT_XML_BASE_XPATH = 'smile_map/address_templates';

    /**
     * @var string
     */
    const FORMAT_TEXT    = 'text';

    /**
     * @var string
     */
    const FORMAT_ONELINE = 'oneline';

    /**
     * @var string
     */
    const FORMAT_HTML    = 'html';

    /**
     * @var string
     */
    const FORMAT_PDF     = 'pdf';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var CountryInformationAcquirerInterface
     */
    private CountryInformationAcquirerInterface $countryInfo;

    /**
     * @var FilterManager
     */
    private FilterManager $filterManager;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cacheInterface;

    /**
     * @var array
     */
    private array $localCache = [];

    /**
     * Constructor.
     *
     * @param FilterManager                         $filterManager  Filter manager used to render address templates.
     * @param StoreManagerInterface                 $storeManager   Store manager.
     * @param ScopeConfigInterface                  $scopeConfig    Store configuration
     * @param CountryInformationAcquirerInterface   $countryInfo    Country info.
     * @param CacheInterface                        $cacheInterface Cache Interface.
     */
    public function __construct(
        FilterManager $filterManager,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CountryInformationAcquirerInterface $countryInfo,
        CacheInterface $cacheInterface
    ) {
        $this->filterManager  = $filterManager;
        $this->storeManager   = $storeManager;
        $this->scopeConfig    = $scopeConfig;
        $this->countryInfo    = $countryInfo;
        $this->cacheInterface = $cacheInterface;
    }

    /**
     * Format the address according to a template.
     *
     * @param AddressInterface $address Address to be formatted.
     * @param string           $format  Format code.
     * @param ?int             $storeId Store id.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return string
     */
    public function formatAddress(AddressInterface $address, string $format = self::FORMAT_TEXT, ?int $storeId = null): string
    {
        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $template  = $this->getAddressTemplate($format, $storeId);
        $variables = $this->getVariables($address);

        return $this->filterManager->template($template, ['variables' => $variables]);
    }

    /**
     * Extract variables used into templates.
     *
     * @param AddressInterface $address Address to be formatted.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return array
     */
    private function getVariables(AddressInterface $address): array
    {
        $variables = $address->getData();

        if ($address->getStreet()) {
            foreach ($address->getStreet() as $index => $streetLine) {
                $index = $index + 1;
                $variables["street{$index}"] = $streetLine;
            }

            $variables['street'] = implode(" ", $address->getStreet());
        }

        if ($address->getCountryId()) {
            $countryId            = $address->getCountryId();
            $variables['country'] = $this->getCountryFullName($countryId);
        }

        return $variables;
    }

    /**
     * Load template from the configuration.
     *
     * @param string $format  Format code.
     * @param int    $storeId Store id.
     *
     * @return string
     */
    private function getAddressTemplate(string $format, int $storeId): string
    {
        $path = self::FORMAT_XML_BASE_XPATH . '/' . $format;

        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Retrieve Country name for current locale, from local cache if possible, or from previous calculation.
     * This is mainly due to the fact that calling CountryInformationAcquirerInterface::getCountryInfo processes a full
     * loading of directory data, without using any cache.
     *
     * @param string $countryId The Country Id
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return mixed
     */
    private function getCountryFullName(string $countryId): mixed
    {
        $store = $this->storeManager->getStore();
        $storeLocale = $this->scopeConfig->getValue(
            'general/locale/code',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $store->getCode()
        );

        $cacheKey = sprintf("%s_%s_%s", $countryId, $store->getId(), $storeLocale);

        if (!isset($this->localCache[$cacheKey])) {
            $data = $this->cacheInterface->load($cacheKey);

            if (!$data) {
                $data = $this->countryInfo->getCountryInfo($countryId)->getFullNameLocale();
                $this->cacheInterface->save(
                    $data,
                    $cacheKey,
                    [\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER],
                    7200
                );
            }

            $this->localCache[$cacheKey] = $data;
        }

        return $this->localCache[$cacheKey];
    }
}
