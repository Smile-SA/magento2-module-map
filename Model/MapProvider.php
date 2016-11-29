<?php

namespace Smile\Map\Model;

use Smile\Map\Api\MapProviderInterface;
use Smile\Map\Helper\Map as MapHelper;

class MapProvider implements MapProviderInterface
{
    /**
     * @var \Smile\Map\Api\MapInterface[]
     */
    private $mapProviders;

    /**
     * @var MapHelper
     */
    private $mapHelper;

    /**
     * Contructor.
     *
     * @param \Smile\Map\Api\MapInterface[] $mapProviders
     */
    public function __construct(MapHelper $mapHelper, array $mapProviders = [])
    {
        $this->mapHelper    = $mapHelper;
        $this->mapProviders = $mapProviders;
    }

    public function getMap()
    {
        $providerIdentifier = $this->mapHelper->getProviderIdentifier();

        if (!isset($this->mapProviders[$providerIdentifier])) {
            throw new \LogicException(__("Map provider %s does not exists", $providerIdentifier));
        }

        return $this->mapProviders[$providerIdentifier];
    }
}