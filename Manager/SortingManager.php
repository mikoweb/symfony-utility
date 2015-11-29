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

namespace vSymfo\Core\Manager;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Manager
 */
class SortingManager
{
    const DEFAULT_FIELD_SORT = 'sort';
    const DEFAULT_FIELD_DIRECTION = 'direction';

    /**
     * @var string
     */
    protected $fieldSort;

    /**
     * @var string
     */
    protected $fieldDirection;

    /**
     * @var array
     */
    protected $allowedColumns;

    /**
     * @var array
     */
    private $custom;

    /**
     * @param string $fieldSort
     * @param string $fieldDirection
     */
    public function __construct(
        $fieldSort = self::DEFAULT_FIELD_SORT,
        $fieldDirection = self::DEFAULT_FIELD_DIRECTION
    ) {
        $this->setFieldSort($fieldSort);
        $this->setFieldDirection($fieldDirection);
        $this->allowedColumns = [];
        $this->custom = [];
    }

    /**
     * @return string
     */
    public function getFieldSort()
    {
        return $this->fieldSort;
    }

    /**
     * @param string $fieldSort
     */
    public function setFieldSort($fieldSort)
    {
        $this->fieldSort = (string)$fieldSort;
    }

    /**
     * @return string
     */
    public function getFieldDirection()
    {
        return $this->fieldDirection;
    }

    /**
     * @param string $fieldDirection
     */
    public function setFieldDirection($fieldDirection)
    {
        $this->fieldDirection = (string)$fieldDirection;
    }

    /**
     * @return array
     */
    public function getAllowedColumns()
    {
        return $this->allowedColumns;
    }

    /**
     * @param array $allowedColumns
     */
    public function setAllowedColumns(array $allowedColumns)
    {
        $this->allowedColumns = $allowedColumns;
    }

    /**
     * @param string $columnName
     * @param callable $sort
     */
    public function addCustomSort($columnName, callable $sort)
    {
        if (!isset($this->custom[$columnName])) {
            $this->custom[$columnName] = [];
        }

        $this->custom[$columnName][] = $sort;
    }

    /**
     * @param string $columnName
     */
    public function removeCustomSort($columnName)
    {
        if (isset($this->custom[$columnName])) {
            unset($this->custom[$columnName]);
        }
    }

    /**
     * @param Request $request
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     *
     * @throws NotFoundHttpException
     */
    public function sort(Request $request, QueryBuilder $queryBuilder)
    {
        $direction = $this->getDirection($request);
        $sort = $this->getSort($request);

        if (!empty($direction) && !empty($sort)) {
            if (!($direction === 'ASC' || $direction === 'DESC')) {
                throw new NotFoundHttpException('Not found direction');
            }

            if (!$this->customSort($queryBuilder, $sort, $direction)) {
                $this->simpleSort($queryBuilder, $sort, $direction);
            }
        }

        return $queryBuilder;
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    public function getDirection(Request $request)
    {
        $direction = $request->get($this->getFieldDirection());

        if (is_null($direction)) {
            return null;
        }

        return strtoupper($direction);
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    public function getSort(Request $request)
    {
        return $request->get($this->getFieldSort());
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $sort
     * @param string $direction
     *
     * @throws NotFoundHttpException
     */
    protected function simpleSort(QueryBuilder $queryBuilder, $sort, $direction)
    {
        if (!preg_match('/^([a-zA-Z0-9_]+[.]{1})?[a-zA-Z0-9_]+$/', $sort)) {
            throw new NotFoundHttpException('Not found sort column');
        }

        $allowed = $this->getAllowedColumns();

        if (!empty($allowed) && !in_array($sort, $allowed)) {
            throw new NotFoundHttpException('Unallowed sort column name');
        }

        $queryBuilder->addOrderBy($sort, $direction);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $sort
     * @param string $direction
     *
     * @return bool
     */
    protected function customSort(QueryBuilder $queryBuilder, $sort, $direction)
    {
        if (!isset($this->custom[$sort]) || empty($this->custom[$sort])) {
            return false;
        }

        for ($count = count($this->custom[$sort]), $i = 0; $i < $count; ++$i) {
            $this->custom[$sort][$i]($queryBuilder, $direction);
        }

        return true;
    }
}
