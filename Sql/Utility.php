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

use vSymfo\Core\Sql\Exception\InvalidColumnNameException;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Sql
 */
class Utility
{
    private function __construct()
    {}

    /**
     * Walidowanie nazwy kolumny
     * @param string $name
     * @throws InvalidColumnNameException
     */
    public static function validColumnName($name)
    {
        if (!preg_match('/^([a-zA-Z0-9_]+[.]{1})?[a-zA-Z0-9_]+$/', $name)) {
            throw new InvalidColumnNameException("Invalid column name: $name");
        }
    }

    /**
     * Sprawdzanie czy tekst zawiera alias kolumny podany w argumecie $alias
     * @param string $alias alias tabeli
     * @param string $text porównywany tekst
     * @return bool
     */
    public static function pregMatchTableAlias($alias, $text)
    {
        if (empty($alias)) {
            return false;
        }

        return (bool)preg_match('/^' . $alias . '[.]{1}[a-zA-Z0-9_]+$/', $text);
    }
}
