<?php

namespace Smile\Map\Api;

interface MapProviderInterface
{
    /**
     * @return MapInteface
     */
    public function getMap();
}