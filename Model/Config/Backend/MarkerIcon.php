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
namespace Smile\Map\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

/**
 * MarkerIcon backend model. Extended to allow svg files.
 *
 * @category Smile
 * @package  Smile\Map
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class MarkerIcon extends File
{
    /**
     * Where the files are stored.
     */
    const UPLOAD_DIR = 'smile_map/marker';

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) method is inherited.
     */
    protected function _getAllowedExtensions(): array
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }
}
