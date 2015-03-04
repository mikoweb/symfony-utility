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

    /**
     * Wstawianie nowych elementów na podstawie łańcucha znaków w formacie:
     * t.column1:desc,t.column2:asc,t.column3:default
     *
     * @param string $text
     */
    public function addFromString($text)
    {
        if (!is_string($text)) {
            throw new \InvalidArgumentException('$text is not string');
        }

        $list = explode(',', $text);
        foreach ($list as $order) {
            $arg = explode(':', $order);
            if (count($arg) < 2) {
                throw new \InvalidArgumentException("Invalid order format");
            }

            $criterion = new OrderCriterion();
            $criterion->setBy($arg[0]);
            $criterion->setOrder($arg[1]);
            $this->add($criterion);
        }
    }
}
