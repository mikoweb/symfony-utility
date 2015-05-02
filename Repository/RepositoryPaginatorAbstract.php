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

namespace vSymfo\Core\Repository;

use Doctrine\ORM\EntityRepository;
use vSymfo\Core\Entity\EntityPaginatorAbstract;

/**
 * Repozytorium z paginacją
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Repository
 */
abstract class RepositoryPaginatorAbstract extends EntityRepository
{
    /**
     * Utwórz nowy paginator
     * @param array $options
     * @return EntityPaginatorAbstract
     * @throws \Exception
     */
    public function createPaginator(array $options)
    {
        $paginator = $this->paginatorBuilder($options);
        if (!($paginator instanceof EntityPaginatorAbstract)) {
            throw new \Exception("Invalid Paginator object");
        }

        $paginator->setRepository($this->getEntityManager()->getRepository($this->getClassName()));

        return $paginator;
    }

    /**
     * Metoda, która tworzy paginator właściwego typu.
     * @param array $options
     * @return EntityPaginatorAbstract
     */
    abstract protected function paginatorBuilder(array $options);
}
