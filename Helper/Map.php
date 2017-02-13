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

use \Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

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
     * @var \Magento\Framework\Locale\Resolver
     */
    private $localeResolver;

    /**
     * Map constructor.
     *
     * @param Context        $context        Application Context
     * @param LocaleResolver $localeResolver Locale Resolver
     */
    public function __construct(Context $context, LocaleResolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
        parent::__construct($context);
    }

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

        if (!isset($config['country'])) {
            $config['country'] = $this->scopeConfig->getValue('general/country/default', ScopeInterface::SCOPE_STORES);
        }

        if (!isset($config['locale'])) {
            $config['locale'] = $this->localeResolver->getLocale();
        }

        return $config;
    }
}
