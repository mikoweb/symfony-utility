<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Interfaces;

use FOS\UserBundle\Model\UserInterface;

interface BlameableEntityInterface
{
    /**
     * @return UserInterface
     */
    public function getCreatedBy(): UserInterface;

    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy);

    /**
     * @return UserInterface
     */
    public function getUpdatedBy(): UserInterface;

    /**
     * @param UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy);
}
