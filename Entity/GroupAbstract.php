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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use vSymfo\Core\Entity\Interfaces\SoftDeleteableInterface;
use vSymfo\Core\Entity\Interfaces\TimestampableInterface;
use vSymfo\Core\Entity\Traits\IrremovableTrait;
use vSymfo\Core\Entity\Traits\SoftDeleteableTrait;
use vSymfo\Core\Entity\Traits\TimestampableTrait;

/**
 * Grupy użytkowników
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
abstract class GroupAbstract implements GroupInterface, SoftDeleteableInterface, TimestampableInterface
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
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name", unique=true, nullable=false)
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * @var string
     *
     * @ORM\Column(name="group_role", type="string")
     */
    protected $groupRole;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->irremovable = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroupRole()
    {
        return $this->groupRole;
    }

    /**
     * @param string $groupRole
     *
     * @return $this
     */
    public function setGroupRole($groupRole)
    {
        $this->groupRole = $groupRole;

        return $this;
    }

    /**
     * @param RoleAbstract $role
     *
     * @return $this
     */
    public function addRole($role)
    {
        if (!$role instanceof RoleAbstract) {
            throw new \InvalidArgumentException('Role is not RoleAbstract');
        }

        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * @param RoleAbstract $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (!$role instanceof RoleAbstract) {
            throw new \InvalidArgumentException('Role is not RoleAbstract');
        }

        return $this->roles->contains($role);
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles->toArray();
        $roles[] = $this->getGroupRole();

        return $roles;
    }

    /**
     * @param RoleAbstract $role
     *
     * @return $this
     */
    public function removeRole($role)
    {
        if (!$role instanceof RoleAbstract) {
            throw new \InvalidArgumentException('Role is not RoleAbstract');
        }

        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = new ArrayCollection($roles);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
