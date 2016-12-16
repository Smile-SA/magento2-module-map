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
namespace Smile\Map\Model\Map;

use \Smile\Map\Helper\Map as MapHelper;
use Magento\Framework\Filter\FilterManager;
use Smile\Map\Api\Data\GeoPointInterface;

/**
 * Default implementation of the MapInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class DefaultMap implements \Smile\Map\Api\MapInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var array
     */
    private $config;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * Constructor.
     *
     * @param string        $identifier    Map identifier.
     * @param MapHelper     $mapHelper     Map helper.
     * @param FilterManager $filterManager Template filter manager.
     */
    public function __construct($identifier, MapHelper $mapHelper, FilterManager $filterManager)
    {
        $this->identifier    = $identifier;
        $this->config        = $mapHelper->getProviderConfiguration($identifier);
        $this->filterManager = $filterManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectionUrl(GeoPointInterface $dest, GeoPointInterface $orig = null)
    {
        $urlTemplate = $this->config['direction_url_template'];

        $data = new \Magento\Framework\DataObject();

        $data->setDestLatitude($dest->getLatitude());
        $data->setDestLongitude($dest->getLongitude());

        if ($orig !== null) {
            $data->setHasOrigin(true);
            $data->setOrigLatitude($orig->getLatitude());
            $data->setOrigLongitude($orig->getLongitude());
        }

        return $this->filterManager->template($urlTemplate, ['variables' => $data->toArray()]);
    }
}
