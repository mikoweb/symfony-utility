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
abstract class EntityFactoryAbstract implements EntityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function entity($className, array $args = [])
    {
        if (!is_string($className)) {
            throw new \InvalidArgumentException('$className is not be string.');
        }

        if (!class_exists($className)) {
            throw new \RuntimeException("Class $className not found.");
        }

        $reflection = new \ReflectionClass($className);
        $entity = $reflection->newInstanceArgs($args);
        $this->aware($entity);

        return $entity;
    }
}
