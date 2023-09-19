<?php

declare(strict_types=1);

namespace Smile\Map\Model\Map;

use Magento\Framework\DataObject;
use Magento\Framework\Filter\FilterManager;
use Smile\Map\Api\Data\GeoPointInterface;
use Smile\Map\Api\MapInterface;
use Smile\Map\Helper\Map as MapHelper;

/**
 * Default implementation of the MapInterface.
 */
class DefaultMap implements MapInterface
{
    private array $config;

    public function __construct(
        private string $identifier,
        private string $name,
        MapHelper $mapHelper,
        private FilterManager $filterManager
    ) {
        $this->config = $mapHelper->getProviderConfiguration($identifier);
    }

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getDirectionUrl(GeoPointInterface $dest, ?GeoPointInterface $orig = null): string
    {
        $urlTemplate = $this->config['direction_url_template'];

        $data = new DataObject();

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
