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

namespace vSymfo\Core\Entity\Paginator;

use \Closure;
use Doctrine\ORM\QueryBuilder;
use vSymfo\Core\Sql\OrderCriterionCollection;
use vSymfo\Core\Sql\SelectValidator;

/**
 * Builder zapytań w paginatorze.
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity_Paginator
 */
class PaginatorBuilder implements PaginatorBuilderInterface
{
    /**
     * Dodatkowe opcje
     * @var array
     */
    protected $options = array();

    /**
     * Własne kryteria sortowania
     * @var OrderCriterionCollection
     */
    protected $orderCriteria;

    /**
     * Czy użyć domyślnych kryteriów sortowania?
     * @var bool
     */
    protected $useDefaultOrderBy = true;

    /**
     * Czy użyć domyślnych dozwolonych kolumn w klauzuli ORDER BY?
     * @var bool
     */
    protected $useDefaultAllowedOrderColumns = true;

    /**
     * Czy użyć domyślnych joinów
     * @var bool
     */
    protected $useDefaultJoins = true;

    /**
     * Czy użyć domyślnej klauzuli where?
     * @var bool
     */
    protected $useDefaultWhere = true;

    /**
     * Czy użyć domyślnej klauzuli select?
     * @var bool
     */
    protected $useDefaultSelect = true;

    /**
     * Funkcja generująca własne zapytanie zliczające.
     * @var Closure
     */
    protected $customCountQuery;

    /**
     * Funkcja generująca własne zapytanie wybierające.
     * @var Closure
     */
    protected $customSelectQuery;

    /**
     * Funkcja obrabia obiekt SelectValidator
     * @var Closure
     */
    protected $customSelectValidator;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $criteria = new OrderCriterionCollection();
        $this->setOrderCriteria($criteria);
        $this->setCustomCountQuery(function (QueryBuilder $qb) {});
        $this->setCustomSelectQuery(function (QueryBuilder $qb) {});
        $this->setCustomSelectValidator(function (SelectValidator $validator) {});
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderCriteria()
    {
        return $this->orderCriteria;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderCriteria(OrderCriterionCollection $orderCriteria)
    {
        $this->orderCriteria = $orderCriteria;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomSelectValidator(Closure $customSelectValidator)
    {
        $this->customSelectValidator = $customSelectValidator;
    }

    /**
     * {@inheritdoc}
     */
    public function customSelectValidator(SelectValidator $validator)
    {
        call_user_func($this->customSelectValidator, $validator);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomCountQuery(Closure $customCountQuery)
    {
        $this->customCountQuery = $customCountQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function customCountQuery(QueryBuilder $qb)
    {
        call_user_func($this->customCountQuery, $qb);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomSelectQuery(Closure $customSelectQuery)
    {
        $this->customSelectQuery = $customSelectQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function customSelectQuery(QueryBuilder $qb)
    {
        call_user_func($this->customSelectQuery, $qb);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function isUseDefaultOrderBy()
    {
        return (bool)$this->useDefaultOrderBy;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseDefaultOrderBy($useDefaultOrderBy)
    {
        $this->useDefaultOrderBy = (bool)$useDefaultOrderBy;
    }

    /**
     * {@inheritdoc}
     */
    public function isUseDefaultAllowedOrderColumns()
    {
        return (bool)$this->useDefaultAllowedOrderColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseDefaultAllowedOrderColumns($useDefaultAllowedOrderColumns)
    {
        $this->useDefaultAllowedOrderColumns = (bool)$useDefaultAllowedOrderColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function isUseDefaultJoins()
    {
        return (bool)$this->useDefaultJoins;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseDefaultJoins($useDefaultJoins)
    {
        $this->useDefaultJoins = (bool)$useDefaultJoins;
    }

    /**
     * {@inheritdoc}
     */
    public function isUseDefaultWhere()
    {
        return (bool)$this->useDefaultWhere;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseDefaultWhere($useDefaultWhere)
    {
        $this->useDefaultWhere = (bool)$useDefaultWhere;
    }

    /**
     * {@inheritdoc}
     */
    public function isUseDefaultSelect()
    {
        return (bool)$this->useDefaultSelect;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseDefaultSelect($useDefaultSelect)
    {
        $this->useDefaultSelect = (bool)$useDefaultSelect;
    }
}
