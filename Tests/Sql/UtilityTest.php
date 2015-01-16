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

use vSymfo\Core\Sql\Utility;
use vSymfo\Core\Sql\Exception\InvalidColumnNameException;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
    public function testUtility()
    {
        // pomyślna walidacja
        Utility::validColumnName('ok');
        Utility::validColumnName('t.ok');
        Utility::validColumnName('t.t');
        Utility::validColumnName('test.ok');
        Utility::validColumnName('1Tg_h657gh_jkjhjg.gh_dfkjhjG_FGFDGF');
        Utility::validColumnName('t_t.tegfdh_gfd');

        // niepomyślna walidacja
        try {
            Utility::validColumnName('^%$%^$%^');
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        try {
            Utility::validColumnName('.t');
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        try {
            Utility::validColumnName('t.');
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        try {
            Utility::validColumnName('t.t.t');
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        try {
            Utility::validColumnName('t.lorem ipsum');
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        // testowanie metody pregMatchTableAlias
        $this->assertTrue(Utility::pregMatchTableAlias('alias', 'alias.column_name'));
        $this->assertTrue(Utility::pregMatchTableAlias('t', 't.column_name'));
        $this->assertFalse(Utility::pregMatchTableAlias('', '.column_name'));
        $this->assertFalse(Utility::pregMatchTableAlias('alias', 'column_name'));
        $this->assertFalse(Utility::pregMatchTableAlias('alias', 'alias..column_name'));
        $this->assertFalse(Utility::pregMatchTableAlias('t', 'alias.column_name'));
    }
}
