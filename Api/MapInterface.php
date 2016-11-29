<?php

namespace Smile\Map\Api;

interface MapInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return array
     */
    public function getConfig();
}