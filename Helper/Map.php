<?php

declare(strict_types=1);

namespace Smile\Map\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smile\Map\Model\Config\Backend\MarkerIcon;

/**
 * Map helper.
 */
class Map extends AbstractHelper
{
    private const MAP_CONFIG_XML_PATH = 'smile_map/map';
    private const SHARED_SETTINGS_NAME = 'all';

    private ReadInterface $mediaDirectory;

    public function __construct(
        Context $context,
        private LocaleResolver $localeResolver,
        private Database $fileStorageHelper,
        private Repository $assetRepository,
        private Filesystem $fileSystem,
        protected StoreManagerInterface $storeManager
    ) {
        $this->mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
        parent::__construct($context);
    }

    /**
     * Returns currently configured map provider.
     */
    public function getProviderIdentifier(): string
    {
        return $this->scopeConfig->getValue(self::MAP_CONFIG_XML_PATH . '/provider');
    }

    /**
     * Returns map configuration by provider.
     */
    public function getProviderConfiguration(string $providerIdentifier): array
    {
        $config = [];

        $mapKeyFunc = function (&$value, $key) use (&$config, $providerIdentifier): void {
            if (
                str_starts_with($key, 'provider_' . $providerIdentifier)
                || str_starts_with($key, 'provider_' . self::SHARED_SETTINGS_NAME)
            ) {
                $prefixes = ['provider_' . $providerIdentifier . '_', 'provider_' . self::SHARED_SETTINGS_NAME . '_'];
                $key = str_replace($prefixes, '', $key);
                $config[$key] = $value;
            }
        };

        $allConfig = $this->scopeConfig->getValue(
            self::MAP_CONFIG_XML_PATH,
            'store',
            $this->storeManager->getStore()->getCode()
        );

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
     * Retrieve custom marker icon to use, if any. Otherwise, returns default leaflet marker.
     */
    private function getMarkerIcon(array $config): string
    {
        $folderName    = MarkerIcon::UPLOAD_DIR;
        $storeLogoPath = $config['markerIcon'] ?? null;
        $path          = $folderName . '/' . $storeLogoPath;

        $logoUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . $path;

        try {
            $defaultFile = "Smile_Map::leaflet/images/marker-icon.png";
            $params      = ['_secure' => $this->_getRequest()->isSecure()];
            $url         = $this->assetRepository->getUrlWithParams($defaultFile, $params);
        } catch (LocalizedException $e) {
            $this->_logger->critical($e->getMessage());
            $url = $this->_urlBuilder->getUrl('', ['_direct' => 'core/index/notFound']);
        }

        if ($storeLogoPath !== null && $this->isFile($path)) {
            $url = $logoUrl;
        }

        return $url;
    }

    /**
     * If DB file storage is on - find there, otherwise - just file_exists.
     */
    private function isFile(string $filename): bool
    {
        if ($this->fileStorageHelper->checkDbUsage() && !$this->getMediaDirectory()->isFile($filename)) {
            $this->fileStorageHelper->saveFileToFilesystem($filename);
        }

        return $this->getMediaDirectory()->isFile($filename);
    }

    /**
     * Get media directory.
     */
    private function getMediaDirectory(): ReadInterface
    {
        return $this->mediaDirectory;
    }
}
