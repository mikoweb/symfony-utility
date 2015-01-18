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

use vSymfo\Core\Sql\Exception\InvalidOrderException;

/**
 * Kryterium w klauzuli ORDER BY
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Sql
 */
class OrderCriterion
{
    /**
     * Kolejność rosnąca
     */
    const ORDER_ASC = "ASC";

    /**
     * Kolejność malejąca
     */
    const ORDER_DESC = "DESC";

    /**
     * Kolejność domyślna
     */
    const ORDER_DEFAULT = "DEFAULT";

    /**
     * Kolejność
     * @var string
     */
    private $order;

    /**
     * Według czego sortować
     * @var string
     */
    private $by;

    /**
     * Domyślna kolejność
     * @var string
     */
    private $defaultOrder;

    /**
     * @param string $defaultOrder
     */
    public function __construct($defaultOrder = OrderCriterion::ORDER_ASC)
    {
        $this->defaultOrder = OrderCriterion::ORDER_ASC;
        $this->setDefaultOrder($defaultOrder);
        $this->order = $this->getDefaultOrder();
    }

    /**
     * @param string $order
     * @return string kolejność: DESC|ASC
     * @throws InvalidOrderException
     */
    protected function _buildOrder($order)
    {
        $uorder = strtoupper((string)$order);
        if ($uorder !== OrderCriterion::ORDER_ASC
            && $uorder !== OrderCriterion::ORDER_DESC
            && $uorder !== OrderCriterion::ORDER_DEFAULT
        ) {
            throw new InvalidOrderException("Unexpected order value: $order");
        }

        return $uorder;
    }

    /**
     * @return string
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }

    /**
     * @param string $order
     */
    public function setDefaultOrder($order)
    {
        $order = $this->_buildOrder($order);
        if ($order === OrderCriterion::ORDER_DEFAULT) {
            $this->defaultOrder = $this->getDefaultOrder();
        } else {
            $this->defaultOrder = $order;
        }
    }

    /**
     * @param bool $useDefault
     * @return string
     */
    public function getOrder($useDefault = true)
    {
        if ($useDefault && $this->order === OrderCriterion::ORDER_DEFAULT) {
            return $this->getDefaultOrder();
        } else {
            return $this->order;
        }
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $this->_buildOrder($order);
    }

    /**
     * @return string
     */
    public function getBy()
    {
        return $this->by;
    }

    /**
     * @param string $by
     */
    public function setBy($by)
    {
        if (!is_string($by)) {
            throw new \UnexpectedValueException("criterion is not string");
        }

        $this->by = $by;
    }
}
