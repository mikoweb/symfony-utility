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

namespace vSymfo\Core\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use vSymfo\Core\Entity\GroupAbstract;
use vSymfo\Core\Entity\RoleAbstract;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage DataFixtures
 */
abstract class AbstractGroupFixture extends AbstractFixture
{
    /**
     * @param string $filename
     * @param ObjectManager $manager
     */
    public function loadGroupRolesFromXml($filename, ObjectManager $manager)
    {
        $this->throwIsNotSubclass($this->getRoleClass(), RoleAbstract::class);
        $allRoles = $manager->getRepository($this->getRoleClass())->findAll();

        foreach ($allRoles as $role) {
            $this->setReference('Role.' . $role->getRole(), $role);
        }

        $xpath = $this->openXmlFile($filename);

        foreach ($xpath->query('/groups/group') as $group) {
            $reference = $group->getAttribute('reference');

            if ($group->hasAttribute('allroles')) {
                $this->addRoles($reference, $allRoles);
            } else {
                $roles = [];

                foreach ($group->getElementsByTagName('role') as $role) {
                    if (!empty($role->nodeValue)) {
                        $roles[] = $role->nodeValue;
                    }
                }

                $this->addRoles($reference, $roles);
            }

        }
    }

    /**
     * @return string
     */
    abstract public function getGroupClass();

    /**
     * @return string
     */
    abstract public function getRoleClass();

    /**
     * @return GroupAbstract
     */
    public function createGroupEntity()
    {
        return $this->createEntity($this->getGroupClass(), GroupAbstract::class);
    }

    /**
     * @param string $reference
     * @param array $roles
     */
    public function addRoles($reference, array $roles)
    {
        /** @var GroupAbstract $group */
        $group = $this->getReference($reference);

        if (!$group instanceof GroupAbstract) {
            throw new \UnexpectedValueException('Reference is not GroupAbstract');
        }

        foreach ($roles as $role) {
            /** @var RoleAbstract $roleEntity */
            $roleEntity = $this->getReference('Role.' . $role);

            if (!$group->hasRole($roleEntity)) {
                $group->addRole($roleEntity);
            }
        }
    }

    /**
     * @param ObjectManager $manager
     * @param string $name
     * @param string $groupRole
     * @param bool $irremovable
     *
     * @return GroupAbstract
     */
    public function createGroup(ObjectManager $manager, $name, $groupRole, $irremovable = true)
    {
        $this->throwIsNotSubclass($this->getGroupClass(), GroupAbstract::class);

        if ($result = $manager->getRepository($this->getGroupClass())->findOneBy([
            'name' => $name
        ])) {
            $manager->persist($result);

            return $result;
        }

        $group = $this->createGroupEntity();
        $group->setName($name);
        $group->setGroupRole($groupRole);
        $group->setIrremovable($irremovable);

        $manager->persist($group);

        return $group;
    }
}
