<?php

declare(strict_types=1);

namespace Smile\Map\Api;

use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Map interface definition.
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
     * @param \Smile\Map\Api\Data\GeoPointInterface $dest Destination for the direction URL.
     * @param \Smile\Map\Api\Data\GeoPointInterface $orig Optional origin for the direction URL.
     * @return string
     */
    public function getDirectionUrl(GeoPointInterface $dest, ?GeoPointInterface $orig = null): string;
}
