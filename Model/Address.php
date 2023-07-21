<?php

declare(strict_types=1);

namespace Smile\Map\Model;

use Magento\Customer\Api\Data\RegionInterface;
use Magento\Framework\Model\AbstractModel;
use Smile\Map\Api\Data\AddressInterface;

/**
 * Default implementation of the AddressInterface.
 */
class Address extends AbstractModel implements AddressInterface
{
    public const RETAILER_ID_FIELD = 'retailer_id';

    /**
     * @inheritdoc
     */
    public function getRegion(): RegionInterface|string|null
    {
        return $this->getData(self::REGION);
    }

    /**
     * @inheritdoc
     */
    public function getRegionId(): ?int
    {
        return (int) $this->getData(self::REGION_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCountryId(): ?string
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * @inheritdoc
     */
    public function getStreet(): array
    {
        return is_array($this->getData(self::STREET))
            ? $this->getData(self::STREET)
            : [$this->getData(self::STREET)];
    }

    /**
     * @inheritdoc
     */
    public function getPostcode(): ?string
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * @inheritdoc
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY);
    }

    /**
     * Set retailer id.
     */
    public function setRetailerId(int $retailerId): self
    {
        return $this->setData(self::RETAILER_ID_FIELD, $retailerId);
    }

    /**
     * @inheritdoc
     */
    public function setCountryId(string $countryId): self
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * @inheritdoc
     */
    public function setRegion(?string $region = null): self
    {
        return $this->setData(self::REGION, $region);
    }

    /**
     * @inheritdoc
     */
    public function setRegionId(int $regionId): self
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * @inheritdoc
     */
    public function setStreet(array|string $street): self
    {
        return $this->setData(self::STREET, $street);
    }

    /**
     * @inheritdoc
     */
    public function setPostcode(string $postcode): self
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * @inheritdoc
     */
    public function setCity(string $city): self
    {
        return $this->setData(self::CITY, $city);
    }
}
