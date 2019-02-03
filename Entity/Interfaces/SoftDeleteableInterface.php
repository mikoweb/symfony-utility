<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Interfaces;

interface SoftDeleteableInterface
{
    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt(?\DateTime $deletedAt = null): void;
}
