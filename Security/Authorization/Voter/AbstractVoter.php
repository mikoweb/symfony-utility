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

namespace vSymfo\Core\Security\Authorization\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use vSymfo\Core\Entity\Interfaces\BlameableEntityInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Security
 */
abstract class AbstractVoter extends Voter implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if (is_null($container)) {
            throw new \InvalidArgumentException('Container is null!');
        }

        $this->container = $container;
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getAuthChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * @param UserInterface $user
     * @param BlameableEntityInterface $object
     *
     * @return bool
     */
    protected function isOwner(UserInterface $user, BlameableEntityInterface $object)
    {
        $creator = $object->getCreatedBy();

        return $creator instanceof UserInterface && $user->getId() === $creator->getId();
    }

    /**
     * @param BlameableEntityInterface $object
     * @param UserInterface $user
     * @param string $role
     *
     * @return bool
     */
    protected function accessOwner(BlameableEntityInterface $object, UserInterface $user, $role)
    {
        return $this->isOwner($user, $object) && $this->getAuthChecker()->isGranted($role);
    }
}
