<?php

declare(strict_types=1);

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
     * @return \Smile\Map\Api\Data\GeoPointInterface
     */
    public function getCoordinates(): ?GeoPointInterface;

    /**
     * Set address coordinates.
     *
     * @param \Smile\Map\Api\Data\GeoPointInterface $coordinates Coordinates.
     * @return $this
     */
    public function setCoordinates(GeoPointInterface $coordinates): self;
}
