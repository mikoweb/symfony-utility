<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Crud;

/**
 * CRUD Factory.
 */
interface CrudFactoryInterface
{
    /**
     * Returns new CRUD object for crudable object.
     *
     * @param CrudableInterface $object
     *
     * @return CrudInterface
     */
    public function create(CrudableInterface $object): CrudInterface;
}
