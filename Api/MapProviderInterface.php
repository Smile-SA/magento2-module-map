<?php

namespace Smile\Map\Api;

/**
 * Map provider interface definition.
 */
interface MapProviderInterface
{
    /**
     * Return configured map.
     *
     * @return MapInterface
     */
    public function getMap(): MapInterface;
}
