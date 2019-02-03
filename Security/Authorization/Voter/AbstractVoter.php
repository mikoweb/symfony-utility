<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Security\Authorization\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Mikoweb\SymfonyUtility\Entity\Interfaces\BlameableEntityInterface;

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
    protected function getAuthChecker(): AuthorizationChecker
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * @param UserInterface $user
     * @param BlameableEntityInterface $object
     *
     * @return bool
     */
    protected function isOwner(UserInterface $user, BlameableEntityInterface $object): bool
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
    protected function accessOwner(BlameableEntityInterface $object, UserInterface $user, string $role): bool
    {
        return $this->isOwner($user, $object) && $this->getAuthChecker()->isGranted($role);
    }
}
