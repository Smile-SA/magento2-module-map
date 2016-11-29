<?php

namespace Smile\Map\Model;

use Smile\Map\Api\Data\GeoPointInterface;

class GeoPoint implements GeoPointInterface
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     *
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->getLatitude();
    }

    public function getLongitude()
    {
        return $this->getLongitude();
    }
}