<?php

declare(strict_types=1);

namespace Smile\Map\Api\Data;

/**
 * Geo point interface definition.
 */
interface GeoPointInterface
{
    public const LATITUDE = 'latitude';
    public const LONGITUDE = 'longitude';

    /**
     * Geopoint latitude.
     *
     * @return float
     */
    public function getLatitude(): float;

    /**
     * Geopoint longitude.
     *
     * @return float
     */
    public function getLongitude(): float;
}
