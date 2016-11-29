<?php

namespace Smile\Map\Api\Data;

interface GeoPointInterface
{
    /**
     * @return float
     */
    public function getLatitude();

    /**
     * @return float
     */
    public function getLongitude();
}