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
 * That will be pass to the factory to make CRUD.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
 */
interface CrudableInterface
{
    /**
     * Gets the class name of CRUD.
     *
     * @return string
     */
    public function getCrudClass();

    /**
     * Returns options for CRUD.
     *
     * @return array
     */
    public function getCrudOptions();
}
