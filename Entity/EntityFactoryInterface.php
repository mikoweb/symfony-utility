<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity;

interface EntityFactoryInterface
{
    /**
     * Gets the entity object.
     *
     * @param string $className Class name of the entity.
     * @param array $args Arguments to pass to a constructor.
     *
     * @return object The entity object.
     */
    public function entity(string $className, array $args = []);

    /**
     * Set up the required objects.
     *
     * @param object $entity The entity object.
     *
     * @return $entity The same object as the argument.
     */
    public function aware($entity);
}
