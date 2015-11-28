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
     * @param Request $request
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     *
     * @throws NotFoundHttpException
     */
    public function sort(Request $request, QueryBuilder $queryBuilder)
    {
        $direction = strtoupper($request->get($this->getFieldDirection()));
        $sort = $request->get($this->getFieldSort());

        if (!empty($direction) && !empty($sort)) {
            if (!($direction === 'ASC' || $direction === 'DESC')) {
                throw new NotFoundHttpException('Not found direction');
            }

            if (!preg_match('/^([a-zA-Z0-9_]+[.]{1})?[a-zA-Z0-9_]+$/', $sort)) {
                throw new NotFoundHttpException('Not found sort column');
            }

            $allowed = $this->getAllowedColumns();

            if (!empty($allowed) && !in_array($sort, $allowed)) {
                throw new NotFoundHttpException('Unallowed sort column name');
            }

            $queryBuilder->addOrderBy($sort, $direction);
        }

        return $queryBuilder;
    }
}
