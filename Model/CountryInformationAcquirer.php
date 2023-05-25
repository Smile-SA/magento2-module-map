<?php

namespace Smile\Map\Model;

use Magento\Directory\Api\Data\CountryInformationInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\Data\CountryInformationFactory;
use Magento\Directory\Model\Data\RegionInformationFactory;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Override default implementation of the Magento CountryInformationAcquirer
 * for find country information even if it is not in the list of authorized countries.
 */
class CountryInformationAcquirer extends \Magento\Directory\Model\CountryInformationAcquirer
{
    public function __construct(
        CountryInformationFactory $countryInformationFactory,
        RegionInformationFactory $regionInformationFactory,
        Data $directoryHelper,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        private Collection $countryCollection
    ) {
        parent::__construct(
            $countryInformationFactory,
            $regionInformationFactory,
            $directoryHelper,
            $scopeConfig,
            $storeManager
        );
    }

    /**
     * @inheritdoc
     */
    public function getCountryInfo($countryId): CountryInformationInterface
    {
        $store = $this->storeManager->getStore();
        $storeLocale = $this->scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORES,
            $store->getCode()
        );

        $countriesCollection = $this->countryCollection->load();
        $regions = $this->directoryHelper->getRegionData();
        $country = $countriesCollection->getItemById($countryId);
        if (!$country) {
            throw new NoSuchEntityException(__('Requested country is not available.'));
        }

        return $this->setCountryInfo($country, $regions, $storeLocale);
    }
}
