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

use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Default implementation of the GeoPointInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class GeoPoint implements GeoPointInterface
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * Constructor.
     *
     * @param float $latitude  Latitude
     * @param float $longitude Longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * {@inheritDoc}
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * {@inheritDoc}
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
