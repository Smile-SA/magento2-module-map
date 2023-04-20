<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Apache License Version 2.0
 */
namespace Smile\Map\Model;

use Smile\Map\Api\MapInterface;
use Smile\Map\Api\MapProviderInterface;
use Smile\Map\Helper\Map as MapHelper;

/**
 * Default implementation of the MapProviderInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class MapProvider implements MapProviderInterface
{
    /**
     * @var MapInterface[]
     */
    private array $mapProviders;

    /**
     * @var MapHelper
     */
    private MapHelper $mapHelper;

    /**
     * Contructor.
     *
     * @param MapHelper $mapHelper    Map helper.
     * @param array     $mapProviders Map providers.
     */
    public function __construct(
        MapHelper $mapHelper,
        array $mapProviders = []
    ) {
        $this->mapHelper    = $mapHelper;
        $this->mapProviders = $mapProviders;
    }

    /**
     * {@inheritDoc}
     */
    public function getMap(): MapInterface
    {
        $providerIdentifier = $this->mapHelper->getProviderIdentifier();

        if (!isset($this->mapProviders[$providerIdentifier])) {
            throw new \LogicException(__("Map provider %s does not exists", $providerIdentifier));
        }

        return $this->mapProviders[$providerIdentifier];
    }

    /**
     * @return array|MapInterface[]
     */
    public function getProviders(): array
    {
        return $this->mapProviders;
    }
}
