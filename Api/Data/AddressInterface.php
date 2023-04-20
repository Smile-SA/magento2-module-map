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
namespace Smile\Map\Api\Data;

use Magento\Customer\Api\Data\RegionInterface;

/**
 * Address interface definition.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface AddressInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STREET     = 'street';
    const POSTCODE   = 'postcode';
    const CITY       = 'city';
    const REGION     = 'region';
    const REGION_ID  = 'region_id';
    const COUNTRY_ID = 'country_id';

    /**
     * Get region.
     *
     * @return RegionInterface|null|string
     */
    public function getRegion(): RegionInterface|null|string;

    /**
     * Get region ID.
     *
     * @return int|null
     */
    public function getRegionId(): int|null;

    /**
     * Two-letter country code in ISO_3166-2 format.
     *
     * @return string|null
     */
    public function getCountryId(): string|null;

    /**
     * Get street.
     *
     * @return string[]|string|null
     */
    public function getStreet(): array|string|null;

    /**
     * Get postcode.
     *
     * @return string|null
     */
    public function getPostcode(): string|null;

    /**
     * Get city name.
     *
     * @return string|null
     */
    public function getCity(): string|null;

    /**
     * Set country id.
     *
     * @param string $countryId Country id.
     *
     * @return $this
     */
    public function setCountryId(string $countryId): self;

    /**
     * Set region.
     *
     * @param ?string $region Region.
     *
     * @return $this
     */
    public function setRegion(?string $region = null): self;

    /**
     * Set region ID.
     *
     * @param int $regionId Region id.
     *
     * @return $this
     */
    public function setRegionId(int $regionId): self;

    /**
     * Set street.
     *
     * @param string[]|string $street Street.
     *
     * @return $this
     */
    public function setStreet(array|string $street): self;

    /**
     * Set postcode.
     *
     * @param string $postcode Postcode.
     *
     * @return $this
     */
    public function setPostcode(string $postcode): self;

    /**
     * Set city name.
     *
     * @param string $city City name.
     *
     * @return $this
     */
    public function setCity(string $city): self;
}
