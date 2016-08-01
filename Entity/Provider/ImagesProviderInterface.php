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

namespace vSymfo\Core\Entity\Provider;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity_Provider
 */
interface ImagesProviderInterface
{
    const DEFAULT_PATH = '/default_image.png';

    /**
     * Gets the public path for the image associated with the object.
     *
     * @param object $obj       The object.
     * @param string $fieldName The field name.
     *
     * @return string The public asset path.
     */
    public function asset($obj, $fieldName);

    /**
     * Gets the same thing asset() or default path.
     *
     * @param object $obj       The object.
     * @param string $fieldName The field name.
     *
     * @return string The public asset path or default path.
     */
    public function assetOrDefault($obj, $fieldName);

    /**
     * Gets the html tags for the image associated with the object.
     *
     * @param object $obj       The object.
     * @param string $fieldName The field name.
     * @param string $format    The render format.
     * @param string $layout    The layout name.
     *
     * @return string The html tags.
     */
    public function render($obj, $fieldName, $format, $layout = null);

    /**
     * Returns the public path.
     *
     * Absolute paths (i.e. http://...) are returned unmodified.
     *
     * @param string $path        A public path
     * @param string $packageName The name of the asset package to use
     *
     * @return string A public path which takes into account the base path and URL path
     */
    public function getUrl($path, $packageName = null);

    /**
     * Gets default path.
     *
     * @return string
     */
    public function getDefaultPath();

    /**
     * Gets filtered path for rendering in the browser.
     * It could be the cached one or an url of filter action.
     *
     * @param string $path          The path where the resolved file is expected.
     * @param string $filter
     * @param array  $runtimeConfig
     * @param string $resolver
     *
     * @return string
     */
    public function imageFilter($path, $filter, array $runtimeConfig = [], $resolver = null);
}
