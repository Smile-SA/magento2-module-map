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

use Smile\Map\Api\Data\AddressInterface;
use Magento\Store\Model\ScopeInterface;

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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Directory\Api\CountryInformationAcquirerInterface
     */
    private $countryInfo;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    private $filterManager;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Filter\FilterManager                    $filterManager Filter manager used to render address templates.
     * @param \Magento\Store\Model\StoreManagerInterface                 $storeManager  Store manager.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface         $scopeConfig   Store configuration
     * @param \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInfo   Country info.
     */
    public function __construct(
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInfo
    ) {
        $this->filterManager = $filterManager;
        $this->storeManager  = $storeManager;
        $this->scopeConfig   = $scopeConfig;
        $this->countryInfo   = $countryInfo;
    }

    /**
     * Format the address according to a template.
     *
     * @param AddressInterface $address Address to be formatted.
     * @param string           $format  Format code.
     * @param int              $storeId Store id.
     *
     * @return string
     */
    public function formatAddress(AddressInterface $address, $format = self::FORMAT_TEXT, $storeId = null)
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
     * @return array
     */
    private function getVariables(AddressInterface $address)
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
            $countryId   = $address->getCountryId();
            $countryInfo = $this->countryInfo->getCountryInfo($countryId);
            $variables['country'] = $countryInfo->getFullNameLocale();
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
    private function getAddressTemplate($format, $storeId)
    {
        $path = self::FORMAT_XML_BASE_XPATH . '/' . $format;

        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
