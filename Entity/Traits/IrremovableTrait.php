<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\ORMException;

trait IrremovableTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="irremovable", options={"default": 0}))
     */
    protected $irremovable;

    /**
     * @return boolean
     */
    public function isIrremovable(): bool
    {
        return $this->irremovable;
    }

    /**
     * @param boolean $irremovable
     *
     * @return $this
     */
    public function setIrremovable(bool $irremovable)
    {
        $this->irremovable = $irremovable;

        return $this;
    }

    /**
     * @ORM\PreRemove
     * @throws ORMException
     */
    public function onPreRemove(): void
    {
        if ($this->isIrremovable()) {
            throw new ORMException('You can not delete irremovable entity.');
        }
    }
}
