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
use Magento\Framework\Model\AbstractModel;

/**
 * Default implementation of the AddressInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Address extends AbstractModel implements AddressInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCountryId()
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegion()
    {
        return $this->getData(self::REGION);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegionId()
    {
        return $this->getData(self::REGION_ID);
    }


    /**
     * {@inheritDoc}
     */
    public function getStreet()
    {
        return is_array($this->getData(self::STREET)) ? $this->getData(self::STREET) : [$this->getData(self::STREET)];
    }

    /**
     * {@inheritDoc}
     */
    public function getPostcode()
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * {@inheritDoc}
     */
    public function setRetailerId($retailerId)
    {
        return $this->setData(self::RETAILER_ID, $retailerId);
    }

    /**
     * {@inheritDoc}
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * {@inheritDoc}
     */
    public function setRegion(\Magento\Customer\Api\Data\RegionInterface $region = null)
    {
        return $this->setData(self::REGION, $region);
    }

    /**
     * {@inheritDoc}
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * {@inheritDoc}
     */
    public function setStreet($street)
    {
        return $this->setData(self::STREET, $street);
    }

    /**
     * {@inheritDoc}
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }
}
