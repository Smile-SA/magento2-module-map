<?php

declare(strict_types=1);

namespace Smile\Map\Api\Data;

use Magento\Customer\Api\Data\RegionInterface;

/**
 * Address interface definition.
 * Method's return type must be specified using return annotation
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
     *
     * @return RegionInterface|string|null
     */
    public function getRegion(): RegionInterface|string|null;

    /**
     * Get region ID.
     *
     * @return ?int
     */
    public function getRegionId(): ?int;

    /**
     * Two-letter country code in ISO_3166-2 format.
     *
     * @return ?string
     */
    public function getCountryId(): ?string;

    /**
     * Get street.
     *
     * @return array
     */
    public function getStreet(): array;

    /**
     * Get postcode.
     *
     * @return ?string
     */
    public function getPostcode(): ?string;

    /**
     * Get city name.
     *
     * @return ?string
     */
    public function getCity(): ?string;

    /**
     * Set country id.
     *
     * @param string $countryId Country id.
     * @return $this
     */
    public function setCountryId(string $countryId): self;

    /**
     * Set region.
     *
     * @param ?string $region Region.
     * @return $this
     */
    public function setRegion(?string $region = null): self;

    /**
     * Set region ID.
     *
     * @param int $regionId Region id.
     * @return $this
     */
    public function setRegionId(string|int $regionId): self;

    /**
     * Set street.
     *
     * @param string[]|string $street Street.
     * @return $this
     */
    public function setStreet(array|string $street): self;

    /**
     * Set postcode.
     *
     * @param string $postcode Postcode.
     * @return $this
     */
    public function setPostcode(string $postcode): self;

    /**
     * Set city name.
     *
     * @param string $city City name.
     * @return $this
     */
    public function setCity(string $city): self;
}
