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
 * Interfejs builder zapytań w paginatorze.
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity_Paginator
 */
interface PaginatorBuilderInterface
{
    /**
     * Własne kryteria sortowania
     * @return OrderCriterionCollection
     */
    public function getOrderCriteria();

    /**
     * Ustaw własne kryteria sortowania
     * @param OrderCriterionCollection $orderCriteria
     */
    public function setOrderCriteria(OrderCriterionCollection $orderCriteria);

    /**
     * Pobierz funkcję obrabiającą obiekt SelectValidator
     * @return Closure
     */
    public function getCustomSelectValidator();

    /**
     * Ustaw funkcję obrabiającą obiekt SelectValidator
     * @param callable $customSelectValidator
     */
    public function setCustomSelectValidator(Closure $customSelectValidator);

    /**
     * Obrabianie obiektu SelectValidator
     * @param SelectValidator $validator
     */
    public function customSelectValidator(SelectValidator $validator);

    /**
     * Pobierz funkcje generującą własne zapytanie zliczające.
     * @return Closure
     */
    public function getCustomCountQuery();

    /**
     * Ustaw funkcje generującą własne zapytanie zliczające.
     * @param callable $customCountQuery
     */
    public function setCustomCountQuery(Closure $customCountQuery);

    /**
     * Własne zapytanie zliczające.
     * @param QueryBuilder $qb
     */
    public function customCountQuery(QueryBuilder $qb);

    /**
     * Pobierz funkcję generującą własne zapytanie wybierające.
     * @return Closure
     */
    public function getCustomSelectQuery();

    /**
     * Ustaw funkcję generującą własne zapytanie wybierające.
     * @param callable $customSelectQuery
     */
    public function setCustomSelectQuery(Closure $customSelectQuery);

    /**
     * Własne zapytanie wybierające.
     * @param QueryBuilder $qb
     */
    public function customSelectQuery(QueryBuilder $qb);

    /**
     * Dodatkowe opcje.
     * @return array
     */
    public function getOptions();

    /**
     * Ustaw dodatkowe opcje.
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Czy użyć domyślnych kryteriów sortowania?
     * @return boolean
     */
    public function isUseDefaultOrderBy();

    /**
     * @param boolean $useDefaultOrderBy
     */
    public function setUseDefaultOrderBy($useDefaultOrderBy);

    /**
     * Czy użyć domyślnych dozwolonych kolumn w klauzuli ORDER BY?
     * @return boolean
     */
    public function isUseDefaultAllowedOrderColumns();

    /**
     * @param boolean $useDefaultAllowedOrderColumns
     */
    public function setUseDefaultAllowedOrderColumns($useDefaultAllowedOrderColumns);

    /**
     * Czy użyć domyślnych joinów?
     * @return boolean
     */
    public function isUseDefaultJoins();

    /**
     * @param boolean $useDefaultJoins
     */
    public function setUseDefaultJoins($useDefaultJoins);

    /**
     * Czy użyć domyślnej klauzuli where?
     * @return boolean
     */
    public function isUseDefaultWhere();

    /**
     * @param boolean $useDefaultWhere
     */
    public function setUseDefaultWhere($useDefaultWhere);

    /**
     * Czy użyć domyślnej klauzuli select?
     * @return boolean
     */
    public function isUseDefaultSelect();

    /**
     * @param boolean $useDefaultSelect
     */
    public function setUseDefaultSelect($useDefaultSelect);
}
