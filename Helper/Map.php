<?php

namespace Smile\Map\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Map extends AbstractHelper
{
    const MAP_CONFIG_XML_PATH  = 'smile_map/map';

    const SHARED_SETTINGS_NAME = 'all';

    public function getProviderIdentifier()
    {
        return $this->scopeConfig->getValue(self::MAP_CONFIG_XML_PATH . '/provider');
    }

    public function getProviderConfiguration($providerIdentifier)
    {
        $config     = [];

        $mapKeyFunc = function(&$value, $key) use (&$config, $providerIdentifier) {
            if (strpos($key, 'provider_' .$providerIdentifier) === 0 || strpos($key, 'provider_' .self::SHARED_SETTINGS_NAME) === 0) {
                $prefixes = ['provider_' .$providerIdentifier . '_', 'provider_' .self::SHARED_SETTINGS_NAME . '_'];
                $key      = str_replace($prefixes, '', $key);
                $config[$key] = $value;
            }
        };

        $allConfig = $this->scopeConfig->getValue(self::MAP_CONFIG_XML_PATH);
        array_walk($allConfig, $mapKeyFunc);

        return $config;
    }
}