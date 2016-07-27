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
     * Gets the html tags for the image associated with the object.
     *
     * @param object $obj       The object.
     * @param string $fieldName The field name.
     *
     * @return string The html tags.
     */
    public function render($obj, $fieldName);
}
