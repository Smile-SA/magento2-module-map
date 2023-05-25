<?php

namespace Smile\Map\Api\Data;

/**
 * Geolocalized address interface definition.
 */
interface GeolocalizedAddressInterface extends AddressInterface
{
    public const COORDINATES = 'coordinates';

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
     * @return $this
     */
    public function setCoordinates(GeoPointInterface $coordinates): self;
}
