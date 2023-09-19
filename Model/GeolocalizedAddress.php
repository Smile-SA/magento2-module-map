<?php

declare(strict_types=1);

namespace Smile\Map\Model;

use Smile\Map\Api\Data\GeolocalizedAddressInterface;
use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Default implementation of the GeolocalizedAddressInterface.
 */
class GeolocalizedAddress extends Address implements GeolocalizedAddressInterface
{
    /**
     * @inheritdoc
     */
    public function getCoordinates(): GeoPointInterface
    {
        return $this->getData(self::COORDINATES);
    }

    /**
     * @inheritdoc
     */
    public function setCoordinates(GeoPointInterface $coordinates): self
    {
        return $this->setData(self::COORDINATES, $coordinates);
    }
}
