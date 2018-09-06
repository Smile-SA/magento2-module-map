<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Maxime Leclercq <maxime.leclercq@smile.fr>
 * @copyright 2018 Smile
 * @license   Apache License Version 2.0
 */
namespace Smile\Map\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Override default implementation of the Magento CountryInformationAcquirer
 * for find country information even if it is not in the list of authorized countries.
 *
 * @category Smile
 * @package  Smile\Map
 */
class CountryInformationAcquirer extends \Magento\Directory\Model\CountryInformationAcquirer
{
    /**
     * CountryInformationAcquirer constructor.
     *
     * @param \Magento\Directory\Model\Data\CountryInformationFactory   $countryInformationFactory
     * @param \Magento\Directory\Model\Data\RegionInformationFactory    $regionInformationFactory
     * @param \Magento\Directory\Helper\Data                            $directoryHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface        $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface                $storeManager
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
     */
    public function __construct(
        \Magento\Directory\Model\Data\CountryInformationFactory $countryInformationFactory,
        \Magento\Directory\Model\Data\RegionInformationFactory $regionInformationFactory,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
    ) {
        $this->countryCollection = $countryCollection;
        parent::__construct($countryInformationFactory, $regionInformationFactory, $directoryHelper, $scopeConfig, $storeManager);
    }

    /**
     * Get country and region information for the store.
     *
     * @param string $countryId Country Id
     *
     * @return \Magento\Directory\Api\Data\CountryInformationInterface
     * @throws NoSuchEntityException
     */
    public function getCountryInfo($countryId)
    {
        $store = $this->storeManager->getStore();
        $storeLocale = $this->scopeConfig->getValue(
            'general/locale/code',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $store->getCode()
        );

        $countriesCollection = $this->countryCollection->load();
        $regions = $this->directoryHelper->getRegionData();
        $country = $countriesCollection->getItemById($countryId);

        if (!$country || !$country->getId()) {
            throw new NoSuchEntityException(
                __(
                    'Requested country is not available.'
                )
            );
        }
        $countryInfo = $this->setCountryInfo($country, $regions, $storeLocale);

        return $countryInfo;
    }
}
