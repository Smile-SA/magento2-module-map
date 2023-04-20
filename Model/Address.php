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

use Magento\Customer\Api\Data\RegionInterface;
use Magento\Framework\Model\AbstractModel;
use Smile\Map\Api\Data\AddressInterface;

/**
 * Default implementation of the AddressInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Address extends AbstractModel implements AddressInterface
{
    const RETAILER_ID_FIELD = 'retailer_id';

    /**
     * {@inheritDoc}
     */
    public function getRegion(): RegionInterface|null|string
    {
        return $this->getData(self::REGION);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegionId(): int|null
    {
        return $this->getData(self::REGION_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountryId(): string|null
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getStreet(): array|string|null
    {
        return is_array($this->getData(self::STREET)) ? $this->getData(self::STREET) : [$this->getData(self::STREET)];
    }

    /**
     * {@inheritDoc}
     */
    public function getPostcode(): string|null
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity(): string|null
    {
        return $this->getData(self::CITY);
    }

    /**
     * @param string|int $retailerId
     *
     * @return $this
     */
    public function setRetailerId(string|int $retailerId): self
    {
        return $this->setData(self::RETAILER_ID_FIELD, $retailerId);
    }

    /**
     * {@inheritDoc}
     */
    public function setCountryId(string $countryId): self
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * {@inheritDoc}
     */
    public function setRegion(?string $region = null): self
    {
        return $this->setData(self::REGION, $region);
    }

    /**
     * {@inheritDoc}
     */
    public function setRegionId(int $regionId): self
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * {@inheritDoc}
     */
    public function setStreet(array|string $street): self
    {
        return $this->setData(self::STREET, $street);
    }

    /**
     * {@inheritDoc}
     */
    public function setPostcode(string $postcode): self
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * {@inheritDoc}
     */
    public function setCity(string $city): self
    {
        return $this->setData(self::CITY, $city);
    }
}
