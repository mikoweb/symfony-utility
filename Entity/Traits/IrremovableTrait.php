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

namespace vSymfo\Core\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\ORMException;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
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
    public function isIrremovable()
    {
        return $this->irremovable;
    }

    /**
     * @param boolean $irremovable
     *
     * @return $this
     */
    public function setIrremovable($irremovable)
    {
        $this->irremovable = $irremovable;

        return $this;
    }

    /**
     * @ORM\PreRemove
     * @throws ORMException
     */
    public function onPreRemove()
    {
        if ($this->isIrremovable()) {
            throw new ORMException('You can not delete irremovable entity.');
        }
    }
}
