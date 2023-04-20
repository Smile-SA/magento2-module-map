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

use Magento\Framework\Filter\FilterManager;
use Smile\Map\Api\Data\GeoPointInterface;
use Smile\Map\Api\MapInterface;
use Smile\Map\Helper\Map as MapHelper;

/**
 * Default implementation of the MapInterface.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class DefaultMap implements MapInterface
{
    /**
     * @var string
     */
    private string $identifier;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var array
     */
    private array $config;

    /**
     * @var FilterManager
     */
    private FilterManager $filterManager;

    /**
     * Constructor.
     *
     * @param string        $identifier    Map identifier.
     * @param string        $name          Map provider name.
     * @param MapHelper     $mapHelper     Map helper.
     * @param FilterManager $filterManager Template filter manager.
     */
    public function __construct(
        string $identifier,
        string $name,
        MapHelper $mapHelper,
        FilterManager $filterManager
    ) {
        $this->identifier    = $identifier;
        $this->name          = $name;
        $this->config        = $mapHelper->getProviderConfiguration($identifier);
        $this->filterManager = $filterManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectionUrl(GeoPointInterface $dest, GeoPointInterface $orig = null): string
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
