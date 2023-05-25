<?php

namespace Smile\Map\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

/**
 * MarkerIcon backend model. Extended to allow svg files.
 */
class MarkerIcon extends File
{
    public const UPLOAD_DIR = 'smile_map/marker';

    /**
     * @inheritdoc
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }
}
