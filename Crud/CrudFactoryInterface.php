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

namespace vSymfo\Core\Crud;

/**
 * CRUD Factory.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
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
    public function create(CrudableInterface $object);
}
