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
use Symfony\Component\Security\Core\Role\RoleInterface;
use vSymfo\Core\Entity\Interfaces\SoftDeleteableInterface;
use vSymfo\Core\Entity\Interfaces\TimestampableInterface;
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
     * @var bool
     *
     * @ORM\Column(type="boolean", name="irremovable", options={default="0"})
     */
    protected $irremovable;

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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     */
    public function setGroupRole($groupRole)
    {
        $this->groupRole = $groupRole;
    }

    /**
     * @param RoleAbstract $role
     *
     * @return $this
     */
    public function addRole(RoleAbstract $role)
    {
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
    public function hasRole(RoleAbstract $role)
    {
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
    public function removeRole(RoleAbstract $role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIrremovable()
    {
        return $this->irremovable;
    }

    /**
     * @param boolean $irremovable
     */
    public function setIrremovable($irremovable)
    {
        $this->irremovable = $irremovable;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
