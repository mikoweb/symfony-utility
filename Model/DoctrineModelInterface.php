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

namespace vSymfo\Core\Model;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Model
 */
interface DoctrineModelInterface
{
    /**
     * @return Registry
     */
    public function getDoctrine();

    /**
     * @return EntityManager;
     */
    public function getEntityManager();

    /**
     * @return EntityRepository
     */
    public function getModelRepository();
}
