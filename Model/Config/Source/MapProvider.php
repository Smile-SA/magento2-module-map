<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Map\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Smile\Map\Model\MapProvider as MapProviderModel;

/**
 * Source Model for Map provider in module configuration.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class MapProvider implements OptionSourceInterface
{
    /**
     * @var MapProviderModel
     */
    private MapProviderModel $mapProvider;

    /**
     * MapProvider constructor.
     *
     * @param MapProviderModel $mapProvider The Map provider
     */
    public function __construct(MapProviderModel $mapProvider)
    {
        $this->mapProvider = $mapProvider;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        $result = [];

        foreach ($this->mapProvider->getProviders() as $provider) {
            $result[] = ['value' => $provider->getIdentifier(), 'label' => $provider->getName()];
        }

        return $result;
    }
}
