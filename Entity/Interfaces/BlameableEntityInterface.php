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

namespace vSymfo\Core\Entity\Interfaces;

use FOS\UserBundle\Model\UserInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
interface BlameableEntityInterface
{
    /**
     * @return User
     */
    public function getCreatedBy();

    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy);

    /**
     * @return User
     */
    public function getUpdatedBy();

    /**
     * @param UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy);
}
