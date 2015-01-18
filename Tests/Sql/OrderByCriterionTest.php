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
use vSymfo\Core\Sql\Exception\InvalidOrderException;

class OrderByCriterionTest extends \PHPUnit_Framework_TestCase
{
    public function testOrder()
    {
        $ordeby = new OrderCriterion(OrderCriterion::ORDER_ASC);
        
        try {
            $ordeby->setDefaultOrder("invalid order");
            $this->assertTrue(false);
        } catch (InvalidOrderException $e) {
            $this->assertTrue(true);
        }

        $this->assertEquals($ordeby->getDefaultOrder(), OrderCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_ASC);

        try {
            $ordeby->setOrder("invalid order");
            $this->assertTrue(false);
        } catch (InvalidOrderException $e) {
            $this->assertTrue(true);
        }

        $ordeby->setDefaultOrder(OrderCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderCriterion::ORDER_ASC);
        $ordeby->setOrder(OrderCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getOrder(false), OrderCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_ASC);

        $ordeby->setDefaultOrder(OrderCriterion::ORDER_DESC);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderCriterion::ORDER_DESC);
        $ordeby->setOrder(OrderCriterion::ORDER_DESC);
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_DESC);

        $ordeby->setDefaultOrder(OrderCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderCriterion::ORDER_ASC);
        $ordeby->setOrder(OrderCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_ASC);

        // wielkość liter nie ma znaczenia
        $ordeby->setOrder("aSc");
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_ASC);
        $ordeby->setOrder("ASc");
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_ASC);
        $ordeby->setOrder("DeSc");
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_DESC);
        $ordeby->setOrder("DESc");
        $this->assertEquals($ordeby->getOrder(), OrderCriterion::ORDER_DESC);

        // testowanie wartości DEFAULT
        $ordeby->setOrder(OrderCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getOrder(), $ordeby->getDefaultOrder());
        $this->assertEquals($ordeby->getOrder(false), OrderCriterion::ORDER_DEFAULT);
    }
}
