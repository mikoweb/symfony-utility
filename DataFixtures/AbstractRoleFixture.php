<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Mikoweb\SymfonyUtility\Entity\RoleAbstract;

abstract class AbstractRoleFixture extends AbstractFixture
{
    /**
     * @var bool
     */
    protected $throwIfExist;

    /**
     * @param ObjectManager $manager
     * @param string $filename
     *
     * @throws \Exception
     */
    public function loadRolesFromXml(ObjectManager $manager, string $filename): void
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
     * @return string
     */
    abstract public function getRoleClass(): string;

    /**
     * @return RoleAbstract
     *
     * @throws \ReflectionException
     */
    public function createRoleEntity(): RoleAbstract
    {
        return $this->createEntity($this->getRoleClass(), RoleAbstract::class);
    }

    /**
     * @param ObjectManager $manager
     * @param string $role
     * @param string $name
     * @param string $tag
     *
     * @return RoleAbstract
     *
     * @throws \ReflectionException
     */
    public function createRole(ObjectManager $manager, string $role, string $name, string $tag): RoleAbstract
    {
        $this->throwIsNotSubclass($this->getRoleClass(), RoleAbstract::class);

        if ($result = $manager->getRepository($this->getRoleClass())->findOneBy([
            'role' => $role
        ])) {
            if ($this->isThrowIfExist()) {
                throw new \UnexpectedValueException($role . ' is exists.');
            }

            $manager->persist($result);
            $this->setReference('Role.' . $role, $result);

            return $result;
        }

        $entity = $this->createRoleEntity();
        $entity->setRole($role);
        $entity->setName($name);
        $entity->setTag($tag);
        $entity->setIrremovable(true);

        $manager->persist($entity);
        $this->setReference('Role.' . $role, $entity);

        return $role;
    }

    /**
     * @return boolean
     */
    public function isThrowIfExist(): bool
    {
        return $this->throwIfExist === null || $this->throwIfExist === true;
    }

    /**
     * @param boolean $throwIfExist
     */
    public function setThrowIfExist(bool $throwIfExist): void
    {
        $this->throwIfExist = $throwIfExist;
    }
}
