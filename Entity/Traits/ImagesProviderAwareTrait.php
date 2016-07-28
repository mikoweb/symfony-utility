<?php

/*
 * This file is part of the vSymfo package.
 *
 * website: www.vision-web.pl
 * (c) Rafał Mikołajun <rafal@vision-web.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vSymfo\Core\Entity\Traits;

use vSymfo\Core\Entity\Provider\ImagesProviderInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
trait ImagesProviderAwareTrait
{
    /**
     * @var ImagesProviderInterface
     */
    protected $imagesProvider;

    /**
     * @param ImagesProviderInterface $imagesProvider
     */
    public function setImagesProvider(ImagesProviderInterface $imagesProvider)
    {
        $this->imagesProvider = $imagesProvider;
    }

    /**
     * Gets the public path for the image
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function assetImage($fieldName)
    {
        return $this->imagesProvider->asset($this, $fieldName);
    }
}
