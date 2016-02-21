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
use vSymfo\Core\Entity\RoleAbstract;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage DataFixtures
 */
abstract class AbstractRoleFixture extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     * @param string $filename
     */
    public function loadRolesFromXml(ObjectManager $manager, $filename)
    {
        $xpath = $this->openXmlFile($filename);

        foreach ($xpath->query('/roles/group') as $tag) {
            foreach ($tag->getElementsByTagName('role') as $role) {
                if ($role->hasAttribute('name')) {
                    $name = 'roles.names.' . $role->getAttribute('name');
                } else {
                    $name = 'roles.names.' . strtolower($role->getAttribute('role'));
                }

                $this->createRole(
                    $manager,
                    $role->getAttribute('role'),
                    $name,
                    'roles.tags.' . $tag->getAttribute('tag')
                );
            }
        }
    }

    /**
     * @return RoleAbstract
     */
    abstract public function createRoleEntity();

    /**
     * @param ObjectManager $manager
     * @param string $role
     * @param string $name
     * @param string $tag
     */
    public function createRole(ObjectManager $manager, $role, $name, $tag)
    {
        $entity = $this->createRoleEntity();
        $entity->setRole($role);
        $entity->setName($name);
        $entity->setTag($tag);
        $entity->setIrremovable(true);

        $manager->persist($entity);

        $this->setReference('Role.' . $role, $entity);
    }
}
