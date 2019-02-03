<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Crud;

/**
 * That will be pass to the factory to make CRUD.
 */
interface CrudableInterface
{
    /**
     * Gets the class name of CRUD.
     *
     * @return string
     */
    public function getCrudClass(): string;

    /**
     * Returns options for CRUD.
     *
     * @return array
     */
    public function getCrudOptions(): array;
}
