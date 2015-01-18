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
use vSymfo\Core\Sql\Exception\UnexpectedColumnNameException;

/**
 * MBudowniczy zapytań wybierających
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Sql
 */
class SelectValidator
{
    /**
     * Domyślna kolejność
     * @var OrderCriterion
     */
    protected $defaultOrderBy;

    /**
     * Dozwolone kolumny w klauzuli ORDER BY
     * @var array
     */
    private $allowedOrderColumns = array();

    public function __construct()
    {
        $this->defaultOrderBy = new OrderCriterion(OrderCriterion::ORDER_ASC);
    }

    /**
     * Domyślna kolejność
     * @return string
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrderBy->getOrder();
    }

    /**
     * Ustaw domyślną kolejność
     * @param string $order
     */
    public function setDefaultOrder($order)
    {
        $this->defaultOrderBy->setDefaultOrder($order);
        $this->defaultOrderBy->setOrder($order);
    }

    /**
     * @return string
     */
    public function getDefaultOrderColumn()
    {
        return $this->defaultOrderBy->getBy();
    }

    /**
     * @param string $name
     * @throws InvalidColumnNameException
     */
    public function setDefaultOrderColumn($name)
    {
        Utility::validColumnName((string)$name);
        $this->defaultOrderBy->setBy($name);
    }

    /**
     * @return array
     */
    public function getAllowedOrderColumns()
    {
        return $this->allowedOrderColumns;
    }

    /**
     * Ustawianie dozwolonych nazw kolumn w klauzuli ORDER BY
     * @param array $columns
     */
    public function setAllowedOrderColumns(array $columns)
    {
        foreach ($columns as $col) {
            if (!is_string($col)) {
                throw new \UnexpectedValueException("column name is not string");
            }

            Utility::validColumnName($col);
        }

        $this->allowedOrderColumns = $columns;
    }

    /**
     * Wstaw nową kolumnę na koniec tablicy
     * @param string $name
     */
    public function addAllowedOrderColumn($name)
    {
        if (!is_string($name)) {
            throw new \UnexpectedValueException("column name is not string");
        }

        Utility::validColumnName($name);

        $this->allowedOrderColumns[] = $name;
    }

    /**
     * Klauzula ORDER BY
     * @param OrderCriterionCollection $criteria
     * @param string|null $allowedAlias
     * @param bool $prependDefaultOrder
     * @return OrderCriterionCollection
     * @throws InvalidColumnNameException
     * @throws UnexpectedColumnNameException
     */
    public function orderBy(OrderCriterionCollection $criteria, $allowedAlias = null, $prependDefaultOrder = false)
    {
        foreach($criteria as $criterion) {
            $criterion->setDefaultOrder($this->getDefaultOrder());
            Utility::validColumnName($criterion->getBy());

            if (!in_array($criterion->getBy(), $this->getAllowedOrderColumns())) {
                throw new UnexpectedColumnNameException("Unexpected column name '" . $criterion->getBy() . "' on ORDER BY.", 1);
            }

            if (is_string($allowedAlias) && !Utility::pregMatchTableAlias($allowedAlias, $criterion->getBy())) {
                throw new UnexpectedColumnNameException("Column name '" . $criterion->getBy() . "' on ORDER BY, contains forbidden table alias.", 2);
            }
        }

        $result = new OrderCriterionCollection();
        $defaultBy = $this->defaultOrderBy->getBy();
        if ($prependDefaultOrder && !empty($defaultBy)) {
            $order = new OrderCriterion();
            $order->setOrder($this->defaultOrderBy->getDefaultOrder());
            $order->setBy($defaultBy);
            $order->setOrder($this->defaultOrderBy->getOrder());
            $result->add($order);
        }
        $result->addRange($criteria->toArray());

        return $result;
    }
}
