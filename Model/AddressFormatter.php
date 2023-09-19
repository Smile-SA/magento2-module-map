<?php

declare(strict_types=1);

namespace Smile\Map\Model;

use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smile\Map\Api\Data\AddressInterface;

/**
 * Address formatter tool.
 */
class AddressFormatter
{
    private const FORMAT_XML_BASE_XPATH = 'smile_map/address_templates';
    public const FORMAT_TEXT = 'text';
    public const FORMAT_ONELINE = 'oneline';
    public const FORMAT_HTML = 'html';
    public const FORMAT_PDF = 'pdf';

    private array $localCache = [];

    public function __construct(
        private FilterManager $filterManager,
        private StoreManagerInterface $storeManager,
        private ScopeConfigInterface $scopeConfig,
        private CountryInformationAcquirerInterface $countryInfo,
        private CacheInterface $cacheInterface
    ) {
    }

    /**
     * Format the address according to a template.
     *
     * @throws NoSuchEntityException
     */
    public function formatAddress(
        AddressInterface $address,
        string $format = self::FORMAT_TEXT,
        ?int $storeId = null
    ): string {
        if ($storeId === null) {
            $storeId = (int) $this->storeManager->getStore()->getId();
        }

        $template  = $this->getAddressTemplate($format, $storeId);
        $variables = $this->getVariables($address);

        return $this->filterManager->template($template, ['variables' => $variables]);
    }

    /**
     * Extract variables used into templates.
     *
     * @throws NoSuchEntityException
     */
    private function getVariables(AddressInterface $address): array
    {
        // @phpstan-ignore-next-line
        $variables = $address->getData();

        if ($address->getStreet()) {
            foreach ($address->getStreet() as $index => $streetLine) {
                ++$index;
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
     * @throws NoSuchEntityException
     */
    private function getCountryFullName(string $countryId): mixed
    {
        $store = $this->storeManager->getStore();
        $storeLocale = $this->scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORES,
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
                    [Config::TYPE_IDENTIFIER],
                    7200
                );
            }

            $this->localCache[$cacheKey] = $data;
        }

        return $this->localCache[$cacheKey];
    }
}
