<?php

declare(strict_types=1);

namespace Smile\Map\Model;

use LogicException;
use Smile\Map\Api\MapInterface;
use Smile\Map\Api\MapProviderInterface;
use Smile\Map\Helper\Map as MapHelper;

/**
 * Default implementation of the MapProviderInterface.
 */
class MapProvider implements MapProviderInterface
{
    /**
     * @param MapInterface[] $mapProviders
     */
    public function __construct(
        private MapHelper $mapHelper,
        private array $mapProviders = []
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getMap(): MapInterface
    {
        $providerIdentifier = $this->mapHelper->getProviderIdentifier();

        if (!isset($this->mapProviders[$providerIdentifier])) {
            throw new LogicException(__("Map provider %s does not exists", $providerIdentifier));
        }

        return $this->mapProviders[$providerIdentifier];
    }

    /**
     * Get map providers.
     *
     * @return MapInterface[]
     */
    public function getProviders(): array
    {
        return $this->mapProviders;
    }
}
