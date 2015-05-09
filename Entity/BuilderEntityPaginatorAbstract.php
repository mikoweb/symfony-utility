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

namespace vSymfo\Core\Entity;

use AshleyDawson\SimplePagination\Paginator;
use Doctrine\ORM\QueryBuilder;
use vSymfo\Core\Sql\SelectValidator;
use vSymfo\Core\Entity\Paginator\PaginatorBuilderInterface;

/**
 * Rozszerzenie klasy EntityPaginatorAbstract o możliwość tworzenia paginatora z gotowymi zapytaniami.
 * Dzięki klasie PaginatorBuilder można dostosowywać zapytanie SQL na zewnątrz obiektu.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
abstract class BuilderEntityPaginatorAbstract extends EntityPaginatorAbstract
{
    /**
     * Tworzenie w pełni skonfigurowanego obiektu paginatora
     * @param PaginatorBuilderInterface $builder
     * @param array $options
     * @return Paginator
     */
    public function getPaginator(PaginatorBuilderInterface $builder, array $options = array())
    {
        $that = $this;
        $builder->setOptions($options);
        $paginator = $this->createPaginator();

        // walidator zapytań
        $sv = new SelectValidator();
        if ($builder->isUseDefaultOrderBy()) {
            $this->defaultOrderBy($sv, $builder->getOptions());
        }

        // dozwolone kolumny w klauzuli ORDER BY
        if ($builder->isUseDefaultAllowedOrderColumns()) {
            $this->defaultAllowedOrderColumns($sv, $builder->getOptions());
        }

        $builder->customSelectValidator($sv);

        // Pass our item total callback
        $paginator->setItemTotalCallback(function () use ($that, $builder) {
            $qb = $that->createQueryBuilderCount($builder->getOptions());

            if (!($qb instanceof QueryBuilder)) {
                throw new \Exception("Invalid QueryBuilder object");
            }

            if ($builder->isUseDefaultJoins()) {
                $that->defaultJoins($qb, true, $builder->getOptions());
            }

            if ($builder->isUseDefaultWhere()) {
                $that->defaultWhere($qb, $builder->getOptions());
            }

            $builder->customCountQuery($qb);
            $query = $qb->getQuery();

            return (int)$query->getSingleScalarResult();
        });

        // Pass our slice callback
        $paginator->setSliceCallback(function ($offset, $length) use ($that, $builder, $sv) {
            $qb = $that->createQueryBuilderSelect();

            if (!($qb instanceof QueryBuilder)) {
                throw new \Exception("Invalid QueryBuilder object");
            }

            if ($builder->isUseDefaultSelect()) {
                $that->defaultSelect($qb, $builder->isUseDefaultJoins(), $builder->getOptions());
            }

            if ($builder->isUseDefaultJoins()) {
                $that->defaultJoins($qb, false, $builder->getOptions());
            }

            if ($builder->isUseDefaultWhere()) {
                $that->defaultWhere($qb, $builder->getOptions());
            }

            $criteria = $sv->orderBy($builder->getOrderCriteria());
            if ($criteria->count()) {
                // kolejność na bazie kolekcji OrderCriterion
                foreach ($criteria as $order) {
                    $qb->addOrderBy($order->getBy(), $order->getOrder());
                }
            } elseif ($builder->isUseDefaultOrderBy()) {
                // domyślna kolejność
                $qb->orderBy($sv->getDefaultOrderColumn(), $sv->getDefaultOrder());
            }

            $builder->customSelectQuery($qb);

            if ($that->isIdsQueryEnabled()) {
                // używanie dodatkowego zapytania w przypadku relacji jeden do wielu
                $qbIds = clone $qb;
                $qbIds
                    ->setFirstResult($offset)
                    ->setMaxResults($length)
                ;
                $that->idsQuery($qb, $qbIds);
            } else {
                // w pozostałych sytuacjach można używać offset i length na zapytaniu głównym
                $qb
                    ->setFirstResult($offset)
                    ->setMaxResults($length)
                ;
            }

            $query = $qb->getQuery();

            return $query->getResult();
        });

        return $paginator;
    }

    /**
     * Tworzenie zapytania zliczającego rekordy
     * @param array $options
     * @return QueryBuilder
     */
    abstract public function createQueryBuilderCount(array $options = array());

    /**
     * Tworzenie zapytania wybierającego
     * @param array $options
     * @return QueryBuilder
     */
    abstract public function createQueryBuilderSelect(array $options = array());

    /**
     * Czy dodatkowe zapytanie wybierające same klucze główne ma być włączone?
     * Ta opcja jest zalecane w przypadku złączeń jeden do wielu.
     * W takim przypadku w głównym zapytaniu zostanie pominięta klauzula LIMIT, OFFSET.
     * @return bool
     */
    abstract public function isIdsQueryEnabled();

    /**
     * Dodatkowe zapytanie używane gdy isIdsQueryEnabled() zwraca true.
     * Zalecane w sytuacji kiedy używamy złączeń jeden do wielu.
     * @param QueryBuilder $qb
     * @param QueryBuilder $qbIds
     */
    abstract public function idsQuery(QueryBuilder $qb, QueryBuilder $qbIds);

    /**
     * Domyślna klauzula SELECT
     * @param QueryBuilder $qb
     * @param bool $useJoins czy użyto joinów
     * @param array $options
     * @return void
     */
    abstract public function defaultSelect(QueryBuilder $qb, $useJoins, array $options = array());

    /**
     * Domyślne kryteria sortowania
     * @param SelectValidator $validator
     * @param array $options
     * @return void
     */
    abstract protected function defaultOrderBy(SelectValidator $validator, array $options = array());

    /**
     * Domyślne dozwolone kolumny w klauzuli ORDER BY
     * @param SelectValidator $validator
     * @param array $options
     * @return void
     */
    abstract protected function defaultAllowedOrderColumns(SelectValidator $validator, array $options = array());

    /**
     * Domyślne joiny
     * @param QueryBuilder $qb
     * @param bool isCount czy to jest zapytanie zliczające
     * @param array $options
     * @return void
     */
    abstract public function defaultJoins(QueryBuilder $qb, $isCount = false, array $options = array());

    /**
     * Domyślne klauzula where
     * @param QueryBuilder $qb
     * @param array $options
     * @return void
     */
    abstract public function defaultWhere(QueryBuilder $qb, array $options = array());
}