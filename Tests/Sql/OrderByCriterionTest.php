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

use vSymfo\Core\Sql\OrderByCriterion;
use vSymfo\Core\Sql\Exception\InvalidOrderException;

class OrderByCriterionTest extends \PHPUnit_Framework_TestCase
{
    public function testOrder()
    {
        $ordeby = new OrderByCriterion(OrderByCriterion::ORDER_ASC);
        
        try {
            $ordeby->setDefaultOrder("invalid order");
            $this->assertTrue(false);
        } catch (InvalidOrderException $e) {
            $this->assertTrue(true);
        }

        $this->assertEquals($ordeby->getDefaultOrder(), OrderByCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_ASC);

        try {
            $ordeby->setOrder("invalid order");
            $this->assertTrue(false);
        } catch (InvalidOrderException $e) {
            $this->assertTrue(true);
        }
        
        $ordeby->setDefaultOrder(OrderByCriterion::ORDER_DESC);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderByCriterion::ORDER_DESC);
        $ordeby->setOrder(OrderByCriterion::ORDER_DESC);
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_DESC);

        $ordeby->setDefaultOrder(OrderByCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderByCriterion::ORDER_DESC);
        $ordeby->setOrder(OrderByCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_DESC);

        $ordeby->setDefaultOrder(OrderByCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getDefaultOrder(), OrderByCriterion::ORDER_ASC);
        $ordeby->setOrder(OrderByCriterion::ORDER_ASC);
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_ASC);

        // wielkość liter nie ma znaczenia
        $ordeby->setOrder("aSc");
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_ASC);
        $ordeby->setOrder("ASc");
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_ASC);
        $ordeby->setOrder("DeSc");
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_DESC);
        $ordeby->setOrder("DESc");
        $this->assertEquals($ordeby->getOrder(), OrderByCriterion::ORDER_DESC);

        // testowanie wartości DEFAULT
        $ordeby->setOrder(OrderByCriterion::ORDER_DEFAULT);
        $this->assertEquals($ordeby->getOrder(), $ordeby->getDefaultOrder());
        $this->assertEquals($ordeby->getOrder(false), OrderByCriterion::ORDER_DEFAULT);
    }
}
