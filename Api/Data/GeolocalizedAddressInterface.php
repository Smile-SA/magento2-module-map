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

use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Geolocalized address interface definition.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface GeolocalizedAddressInterface extends AddressInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const COORDINATES = 'coordinates';
    /**#@-*/

    /**
     * Get address coordinates.
     *
     * @return GeoPointInterface
     */
    public function getCoordinates(): GeoPointInterface;

    /**
     * Set address coordinates.
     *
     * @param GeoPointInterface $coordinates Coordinates.
     *
     * @return $this
     */
    public function setCoordinates(GeoPointInterface $coordinates): self;
}
