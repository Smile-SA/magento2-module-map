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
namespace Smile\Map\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Map helper.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Map extends AbstractHelper
{
    /**
     * @var string
     */
    const MAP_CONFIG_XML_PATH  = 'smile_map/map';

    /**
     * @var string
     */
    const SHARED_SETTINGS_NAME = 'all';

    /**
     * Returns currently configured map provider.
     *
     * @return string
     */
    public function getProviderIdentifier()
    {
        return $this->scopeConfig->getValue(self::MAP_CONFIG_XML_PATH . '/provider');
    }

    /**
     * Returns map configuration by provider.
     *
     * @param string $providerIdentifier Provider identifier.
     *
     * @return mixed[]
     */
    public function getProviderConfiguration($providerIdentifier)
    {
        $config     = [];

        $mapKeyFunc = function (&$value, $key) use (&$config, $providerIdentifier) {
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
