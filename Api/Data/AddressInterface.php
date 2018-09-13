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
     * @return \Magento\Customer\Api\Data\RegionInterface|null
     */
    public function getRegion();

    /**
     * Get region ID.
     *
     * @return int|null
     */
    public function getRegionId();

    /**
     * Two-letter country code in ISO_3166-2 format.
     *
     * @return string|null
     */
    public function getCountryId();

    /**
     * Get street.
     *
     * @return string[]|string|null
     */
    public function getStreet();

    /**
     * Get postcode.
     *
     * @return string|null
     */
    public function getPostcode();

    /**
     * Get city name.
     *
     * @return string|null
     */
    public function getCity();

    /**
     * Set country id.
     *
     * @param string $countryId Country id.
     *
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * Set region.
     *
     * @param string $region Region.
     *
     * @return $this
     */
    public function setRegion($region = null);

    /**
     * Set region ID.
     *
     * @param int $regionId Region id.
     *
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Set street.
     *
     * @param string[]|string $street Street.
     *
     * @return $this
     */
    public function setStreet($street);

    /**
     * Set postcode.
     *
     * @param string $postcode Postcode.
     *
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Set city name.
     *
     * @param string $city City name.
     *
     * @return $this
     */
    public function setCity($city);
}
