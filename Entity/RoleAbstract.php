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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use vSymfo\Core\Entity\Interfaces\SoftDeleteableInterface;
use vSymfo\Core\Entity\Interfaces\TimestampableInterface;
use vSymfo\Core\Entity\Traits\IrremovableTrait;
use vSymfo\Core\Entity\Traits\SoftDeleteableTrait;
use vSymfo\Core\Entity\Traits\TimestampableTrait;

/**
 * Role użytkownika
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
abstract class RoleAbstract implements RoleInterface, SoftDeleteableInterface, TimestampableInterface
{
    use SoftDeleteableTrait;
    use TimestampableTrait;
    use IrremovableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="role", unique=true, nullable=false)
     */
    protected $role;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name", unique=true, nullable=false)
     */
    protected $name;

    public function __construct()
    {
        $this->irremovable = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getRole();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
