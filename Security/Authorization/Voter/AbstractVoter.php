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
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter as BaseVoter;
use vSymfo\Core\Entity\Interfaces\BlameableEntityInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Security
 */
abstract class AbstractVoter extends BaseVoter implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var AuthorizationChecker
     */
    protected $authChecker;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if (is_null($container)) {
            throw new \RuntimeException('You can not set the null');
        }

        if (!is_null($this->container)) {
            throw new \RuntimeException('The container has already been set');
        }

        $this->container = $container;
        $this->authChecker = $container->get('security.authorization_checker');
    }

    /**
     * @param UserInterface $user
     * @param BlameableEntityInterface $entity
     *
     * @return bool
     */
    protected function isOwner(UserInterface $user, BlameableEntityInterface $entity)
    {
        $createdBy = $entity->getCreatedBy();

        if (!$createdBy instanceof UserInterface) {
            return false;
        }

        return $createdBy->getId() === $user->getId();
    }

    /**
     * @param BlameableEntityInterface $entity
     * @param UserInterface $user
     * @param string $ownerRole
     *
     * @return bool
     */
    protected function accessOwner(BlameableEntityInterface $entity, UserInterface $user, $ownerRole)
    {
        return $this->isOwner($user, $entity) && $this->authChecker->isGranted($ownerRole);
    }
}
