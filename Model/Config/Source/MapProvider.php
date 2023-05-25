<?php

namespace Smile\Map\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Smile\Map\Model\MapProvider as MapProviderModel;

/**
 * Source Model for Map provider in module configuration.
 */
class MapProvider implements OptionSourceInterface
{
    public function __construct(private MapProviderModel $mapProvider)
    {
    }

    /**
     * @inheritdoc
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
