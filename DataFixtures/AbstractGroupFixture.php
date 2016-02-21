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
     */
    public function loadGroupRolesFromXml($filename)
    {
        $xpath = $this->openXmlFile($filename);

        foreach ($xpath->query('/groups/group') as $group) {
            $roles = [];

            foreach ($group->getElementsByTagName('role') as $role) {
                if (!empty($role->nodeValue)) {
                    $roles[] = $role->nodeValue;
                }
            }

            $this->addRoles($group->getAttribute('reference'), $roles);
        }
    }

    /**
     * @return GroupAbstract
     */
    abstract public function createGroupEntity();

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
            $group->addRole($roleEntity);
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
        $group = $this->createGroupEntity();
        $group->setName($name);
        $group->setGroupRole($groupRole);
        $group->setIrremovable($irremovable);

        $manager->persist($group);

        return $group;
    }
}
