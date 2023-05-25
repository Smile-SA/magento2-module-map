<?php

namespace Smile\Map\Api\Data;

use Magento\Customer\Api\Data\RegionInterface;

/**
 * Address interface definition.
 */
interface AddressInterface
{
    public const STREET = 'street';
    public const POSTCODE = 'postcode';
    public const CITY = 'city';
    public const REGION = 'region';
    public const REGION_ID = 'region_id';
    public const COUNTRY_ID = 'country_id';

    /**
     * Get region.
     */
    public function getRegion(): RegionInterface|string|null;

    /**
     * Get region ID.
     */
    public function getRegionId(): ?int;

    /**
     * Two-letter country code in ISO_3166-2 format.
     */
    public function getCountryId(): ?string;

    /**
     * Get street.
     */
    public function getStreet(): array;

    /**
     * Get postcode.
     */
    public function getPostcode(): ?string;

    /**
     * Get city name.
     */
    public function getCity(): ?string;

    /**
     * Set country id.
     */
    public function setCountryId(string $countryId): self;

    /**
     * Set region.
     */
    public function setRegion(?string $region = null): self;

    /**
     * Set region ID.
     */
    public function setRegionId(int $regionId): self;

    /**
     * Set street.
     */
    public function setStreet(array|string $street): self;

    /**
     * Set postcode.
     */
    public function setPostcode(string $postcode): self;

    /**
     * Set city name.
     */
    public function setCity(string $city): self;
}
