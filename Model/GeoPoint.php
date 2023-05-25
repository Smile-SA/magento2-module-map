<?php

namespace Smile\Map\Model;

use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Default implementation of the GeoPointInterface.
 */
class GeoPoint implements GeoPointInterface
{
    public function __construct(private float $latitude, private float $longitude)
    {
    }

    /**
     * @inheritdoc
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @inheritdoc
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
