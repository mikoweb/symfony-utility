<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity;

abstract class EntityFactoryAbstract implements EntityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function entity(string $className, array $args = [])
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
