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
namespace Smile\Map\Api;

use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Map interface definition.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface MapInterface
{
    /**
     * Returns current map provider identifier.
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Returns current map provider name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return current map configuration.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Returns the direction URL using the current provider.
     *
     * @param GeoPointInterface $dest Destination for the direction URL.
     * @param GeoPointInterface $orig Optional origin for the direction URL.
     *
     * @return string
     */
    public function getDirectionUrl(GeoPointInterface $dest, GeoPointInterface $orig = null): string;
}
