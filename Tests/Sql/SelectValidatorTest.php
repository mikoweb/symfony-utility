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

use vSymfo\Core\Sql\OrderCriterion;
use vSymfo\Core\Sql\OrderCriterionCollection;
use vSymfo\Core\Sql\SelectValidator;
use vSymfo\Core\Sql\Exception\InvalidOrderException;
use vSymfo\Core\Sql\Exception\InvalidColumnNameException;
use vSymfo\Core\Sql\Exception\UnexpectedColumnNameException;

class SelectValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testOrder()
    {
        $sb = new SelectValidator();

        try {
            $sb->setDefaultOrder("invalid order");
            $this->assertTrue(false);
        } catch (InvalidOrderException $e) {
            $this->assertTrue(true);
        }

        $sb->setDefaultOrder(OrderCriterion::ORDER_DESC);
        $this->assertEquals($sb->getDefaultOrder(), OrderCriterion::ORDER_DESC);

        $sb->setDefaultOrder(OrderCriterion::ORDER_DEFAULT);
        $this->assertEquals($sb->getDefaultOrder(), OrderCriterion::ORDER_DESC);

        $sb->setDefaultOrder(OrderCriterion::ORDER_ASC);
        $this->assertEquals($sb->getDefaultOrder(), OrderCriterion::ORDER_ASC);
    }

    public function testOrderColumn()
    {
        $sb = new SelectValidator();
        try {
            $sb->setDefaultOrderColumn("^&%^&$");
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        try {
            $sb->setDefaultOrderColumn("a.b.cdef");
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertTrue(true);
        }

        $sb->setDefaultOrderColumn("test");
        $this->assertEquals("test", $sb->getDefaultOrderColumn());
        $sb->setDefaultOrderColumn("test_test");
        $this->assertEquals("test_test", $sb->getDefaultOrderColumn());
        $sb->setDefaultOrderColumn("alias.test");
        $this->assertEquals("alias.test", $sb->getDefaultOrderColumn());
        $sb->setDefaultOrderColumn("ali_as.te_st");
        $this->assertEquals("ali_as.te_st", $sb->getDefaultOrderColumn());

        // dozwolone kolumny
        $sb->setAllowedOrderColumns(array("a.column_1", "b.column_2", "a.column_3"));
        $t = array("a.column_1", "b.column_2", "a.column_3");
        $l = count($t);
        foreach ($sb->getAllowedOrderColumns() as $k=>$v) {
            $this->assertEquals($v, $t[$k]);
        }
        $sb->addAllowedOrderColumn("c.column_4");
        $t = $sb->getAllowedOrderColumns();
        $this->assertEquals($t[$l], "c.column_4");

        // testowanie metody orderBy
        $criteria = new OrderCriterionCollection();

        $order = new OrderCriterion();
        $order->setOrder(OrderCriterion::ORDER_DEFAULT);
        $order->setBy("invalid name");
        $criteria->add($order);

        // testowanie nieakceptowalnych nazw kolumn
        try {
            $sb->orderBy($criteria);
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertEquals($order->getBy(), "invalid name");
        }

        try {
            $order->setBy("invalid.invalid.invalid");
            $sb->orderBy($criteria);
            $this->assertTrue(false);
        } catch (InvalidColumnNameException $e) {
            $this->assertEquals($order->getBy(), "invalid.invalid.invalid");
        }

        // testowanie dozwolnych kolumn
        $order->setBy("a.column_1");
        $result = $sb->orderBy($criteria);
        // jeżeli walidacja przebiegła pomyślnie to metoda zwórci dokładnie ten sam obiekt co w pierwszym argumencie
        $this->assertTrue($result instanceof OrderCriterionCollection);

        // niedozwolona kolumna
        try {
            $order->setBy("unknown");
            $sb->orderBy($criteria);
            $this->assertTrue(false);
        } catch (UnexpectedColumnNameException $e) {
            $this->assertEquals($order->getBy(), "unknown");
            $this->assertEquals($e->getCode(), 1);
        }

        // testowanie wartość zwracanej kolekcji
        $sb = new SelectValidator();
        $criteria = new OrderCriterionCollection();
        $sb->setDefaultOrder(OrderCriterion::ORDER_DESC);
        $sb->setDefaultOrderColumn("d.default_column");
        $sb->setAllowedOrderColumns(array("a.column_1", "b.column_2", "a.column_3", "column_4"));

        $order = new OrderCriterion();
        $order->setOrder(OrderCriterion::ORDER_DEFAULT);
        $order->setBy("a.column_1");
        $criteria->add($order);

        $order = new OrderCriterion();
        $order->setOrder(OrderCriterion::ORDER_ASC);
        $order->setBy("a.column_3");
        $criteria->add($order);

        $order = new OrderCriterion();
        $order->setOrder(OrderCriterion::ORDER_DESC);
        $order->setBy("column_4");
        $criteria->add($order);

        // to powinno przejść walidacje
        $result = $sb->orderBy($criteria);
        $this->assertEquals($result->at(0)->getOrder(), OrderCriterion::ORDER_DESC);
        $this->assertEquals($result->at(0)->getBy(), "a.column_1");
        $this->assertEquals($result->at(1)->getOrder(), OrderCriterion::ORDER_ASC);
        $this->assertEquals($result->at(1)->getBy(), "a.column_3");
        $this->assertEquals($result->at(2)->getOrder(), OrderCriterion::ORDER_DESC);
        $this->assertEquals($result->at(2)->getBy(), "column_4");

        // pierwszy element powinien mieć wartości domyślne
        $result = $sb->orderBy($criteria, null, true);
        $this->assertEquals($result->at(0)->getOrder(), OrderCriterion::ORDER_DESC);
        $this->assertEquals($result->at(0)->getBy(), "d.default_column");
        $this->assertEquals($result->at(1)->getOrder(), OrderCriterion::ORDER_DESC);
        $this->assertEquals($result->at(1)->getBy(), "a.column_1");
        $this->assertEquals($result->at(2)->getOrder(), OrderCriterion::ORDER_ASC);
        $this->assertEquals($result->at(2)->getBy(), "a.column_3");
        $this->assertEquals($result->at(3)->getOrder(), OrderCriterion::ORDER_DESC);
        $this->assertEquals($result->at(3)->getBy(), "column_4");

        // teraz nie może przejść walidacji, bo wstawiono argument $allowedAlias
        try {
            $sb->orderBy($criteria, 'a');
            $this->assertTrue(false);
        } catch (UnexpectedColumnNameException $e) {
            $this->assertEquals($e->getCode(), 2);
        }

        // wstawiono nieakceptowalną nazwę kolumny
        try {
            $order = new OrderCriterion();
            $order->setBy("gfdghgfjkkfjdshkfjhj");
            $criteria->add($order);
            $sb->orderBy($criteria);
            $this->assertTrue(false);
        } catch (UnexpectedColumnNameException $e) {
            $this->assertEquals($e->getCode(), 1);
        }
    }
}
