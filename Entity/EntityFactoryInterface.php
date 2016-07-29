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

namespace vSymfo\Core\Entity;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
interface EntityFactoryInterface
{
    /**
     * Gets the entity object.
     *
     * @param string $className Class name of the entity.
     *
     * @return object The entity object.
     */
    public function entity($className);

    /**
     * Set up the required objects.
     *
     * @param object $entity The entity object.
     *
     * @return $entity The same object as the argument.
     */
    public function aware($entity);
}
