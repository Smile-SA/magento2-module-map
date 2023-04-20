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

use Magento\Directory\Api\Data\CountryInformationInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\Data\CountryInformationFactory;
use Magento\Directory\Model\Data\RegionInformationFactory;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var Collection
     */
    private Collection $countryCollection;

    /**
     * CountryInformationAcquirer constructor.
     *
     * @param CountryInformationFactory $countryInformationFactory
     * @param RegionInformationFactory  $regionInformationFactory
     * @param Data                      $directoryHelper
     * @param ScopeConfigInterface      $scopeConfig
     * @param StoreManagerInterface     $storeManager
     * @param Collection                $countryCollection
     */
    public function __construct(
        CountryInformationFactory $countryInformationFactory,
        RegionInformationFactory $regionInformationFactory,
        Data $directoryHelper,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Collection $countryCollection
    ) {
        $this->countryCollection = $countryCollection;
        parent::__construct($countryInformationFactory, $regionInformationFactory, $directoryHelper, $scopeConfig, $storeManager);
    }

    /**
     * Get country and region information for the store.
     *
     * @param string $countryId Country Id
     *
     * @return CountryInformationInterface
     * @throws NoSuchEntityException
     */
    public function getCountryInfo($countryId): CountryInformationInterface
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

        return $this->setCountryInfo($country, $regions, $storeLocale);
    }
}
