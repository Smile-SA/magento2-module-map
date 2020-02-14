<?php
/**
 * DISCLAIMER
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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use \Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Asset\Repository;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smile\Map\Model\Config\Backend\MarkerIcon;

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
    const MAP_CONFIG_XML_PATH = 'smile_map/map';

    /**
     * @var string
     */
    const SHARED_SETTINGS_NAME = 'all';

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    private $localeResolver;

    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    private $fileStorageHelper;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $fileSystem;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    private $mediaDirectory;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * Map constructor.
     *
     * @param Context        		$context           Application Context
     * @param LocaleResolver		$localeResolver    Locale Resolver
     * @param Database      		$fileStorageHelper File Storage Helper
     * @param Repository    		$assetRepository   Asset Repository
     * @param Filesystem     		$fileSystem        File System
     * @param StoreManagerInterface $storeManager	   Store Manager
     */
    public function __construct(
        Context $context,
        LocaleResolver $localeResolver,
        Database $fileStorageHelper,
        Repository $assetRepository,
        Filesystem $fileSystem,
		StoreManagerInterface $storeManager
    ) {
        $this->localeResolver    = $localeResolver;
        $this->fileStorageHelper = $fileStorageHelper;
        $this->fileSystem        = $fileSystem;
        $this->assetRepository   = $assetRepository;
		$this->storeManager 	 = $storeManager;
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
        $config = [];

        $mapKeyFunc = function (&$value, $key) use (&$config, $providerIdentifier) {
            if (strpos($key, 'provider_' . $providerIdentifier) === 0 || strpos($key, 'provider_' . self::SHARED_SETTINGS_NAME) === 0) {
                $prefixes     = ['provider_' . $providerIdentifier . '_', 'provider_' . self::SHARED_SETTINGS_NAME . '_'];
                $key          = str_replace($prefixes, '', $key);
                $config[$key] = $value;
            }
        };

		$allConfig = $this->scopeConfig->getValue(self::MAP_CONFIG_XML_PATH, 'store', $this->storeManager->getStore()->getCode());
        
        array_walk($allConfig, $mapKeyFunc);

        if (!isset($config['country'])) {
            $config['country'] = $this->scopeConfig->getValue('general/country/default', ScopeInterface::SCOPE_STORES);
        }

        if (!isset($config['locale'])) {
            $config['locale'] = $this->localeResolver->getLocale();
        }

        $config['markerIcon'] = $this->getMarkerIcon($config);

        return $config;
    }

    /**
     * Retrieve custom marker icon to use, if any. Otherwise returns default leaflet marker.
     *
     * @param array $config The Map configuration.
     *
     * @return string
     */
    private function getMarkerIcon($config)
    {
        $folderName    = MarkerIcon::UPLOAD_DIR;
        $storeLogoPath = isset($config['markerIcon']) ? $config['markerIcon'] : null;
        $path          = $folderName . '/' . $storeLogoPath;

        $logoUrl = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;

        try {
            $defaultFile = "Smile_Map::leaflet/images/marker-icon.png";
            $params      = ['_secure' => $this->_getRequest()->isSecure()];
            $url         = $this->assetRepository->getUrlWithParams($defaultFile, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->critical($e);
            $url = $this->_urlBuilder->getUrl('', ['_direct' => 'core/index/notFound']);
        }

        if ($storeLogoPath !== null && $this->isFile($path)) {
            $url = $logoUrl;
        }

        return $url;
    }

    /**
     * If DB file storage is on - find there, otherwise - just file_exists
     *
     * @param string $filename relative path
     *
     * @return bool
     */
    private function isFile($filename)
    {
        if ($this->fileStorageHelper->checkDbUsage() && !$this->getMediaDirectory()->isFile($filename)) {
            $this->fileStorageHelper->saveFileToFilesystem($filename);
        }

        return $this->getMediaDirectory()->isFile($filename);
    }

    /**
     * Get media directory
     *
     * @return \Magento\Framework\Filesystem\Directory\Read
     */
    private function getMediaDirectory()
    {
        if (!$this->mediaDirectory) {
            $this->mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
        }

        return $this->mediaDirectory;
    }
}
