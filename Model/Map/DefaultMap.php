<?php

namespace Smile\Map\Model\Map;

use \Smile\Map\Helper\Map as MapHelper;

class DefaultMap implements \Smile\Map\Api\MapInterface
{
    private $identifier;

    private $config;

    public function __construct($identifier, MapHelper $mapHelper)
    {
        $this->identifier = $identifier;
        $this->config     = $mapHelper->getProviderConfiguration($identifier);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}