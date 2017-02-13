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

/**
 * Source Model for Map provider in module configuration.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class MapProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Smile\Map\Model\MapProvider
     */
    private $mapProvider;

    /**
     * MapProvider constructor.
     *
     * @param \Smile\Map\Model\MapProvider $mapProvider The Map provider
     */
    public function __construct(\Smile\Map\Model\MapProvider $mapProvider)
    {
        $this->mapProvider = $mapProvider;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $result = [];

        foreach ($this->mapProvider->getProviders() as $provider) {
            $result[] = ['value' => $provider->getIdentifier(), 'label' => $provider->getName()];
        }

        return $result;
    }
}
