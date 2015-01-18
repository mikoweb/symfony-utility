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

namespace vSymfo\Core\Sql;

use Collections\Collection;

/**
 * Kolekcja obiektów OrderCriterion
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Sql
 */
class OrderCriterionCollection extends Collection
{
    public function __construct()
    {
        parent::__construct('vSymfo\Core\Sql\OrderCriterion');
    }
}
