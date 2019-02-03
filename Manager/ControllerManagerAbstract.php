<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Mikoweb\SymfonyUtility\Entity\EntityFactoryInterface;

/**
 * Common actions. It is usually usage in the controller.
 */
abstract class ControllerManagerAbstract implements ControllerManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var EntityFactoryInterface
     */
    protected $entityFactory;

    /**
     * @param EntityManager $manager
     * @param FormFactory $formFactory
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(
        EntityManager $manager,
        FormFactory $formFactory,
        EntityFactoryInterface $entityFactory
    ) {
        $this->manager = $manager;
        $this->formFactory = $formFactory;
        $this->entityFactory = $entityFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createEntity(array $args = [])
    {
        return $this->entityFactory->entity($this->getEntityClass(), $args);
    }

    /**
     * {@inheritdoc}
     */
    public function findEntity(Request $request, ?string $queryKey = null)
    {
        $entity = $this->manager->getRepository($this->getEntityClass())->find(
            is_string($queryKey) ? $request->query->get($queryKey) : $request->attributes->get('id'));

        if (is_null($entity)) {
            throw new NotFoundHttpException('Entity not found.');
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm($data = null, array $options = [], ?string $type = null): Form
    {
        $formType = is_null($type) ? $this->formType() : $type;
        $form = $this->formFactory
            ->createBuilder($formType, $data, $options)
            ->getForm()
        ;

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save($data): void
    {
        $this->invalidEntityException($data);
        $this->manager->persist($data);
        $this->manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data): void
    {
        $this->invalidEntityException($data);
        $this->manager->remove($data);
        $this->manager->flush();
    }

    /**
     * @param mixed $data
     *
     * @throws \UnexpectedValueException
     */
    protected function invalidEntityException($data): void
    {
        if (!$this->isRightEntity($data)) {
            throw new \InvalidArgumentException();
        }
    }
}
