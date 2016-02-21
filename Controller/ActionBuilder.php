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

namespace vSymfo\Core\Controller;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use vSymfo\Core\Event\ActionBuilderEvent;
use vSymfo\Core\Manager\ControllerManagerInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller
 */
class ActionBuilder
{
    const FORM_TYPE_NEW = 0;
    const FORM_TYPE_EDIT = 1;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Form|null
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var array
     */
    private $allowedEventNames;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
        $this->form = null;
        $this->entity = null;
        $this->allowedEventNames = [];
        $this->generateAllowedEventNames();
    }

    /**
     * @param ActionBuilder $builder
     * @param string $formType
     * @param ControllerManagerInterface $manager
     * @param mixed $entity
     */
    public function createForm(ActionBuilder $builder, $formType, ControllerManagerInterface $manager, $entity = null)
    {
        if (is_null($entity)) {
            $entity = $manager->createEntity();
        }

        $builder->setEntity($entity);

        switch ($formType) {
            case self::FORM_TYPE_NEW:
                $form = $manager->buildFormForNew($entity);
                break;
            case self::FORM_TYPE_EDIT:
                $form = $manager->buildFormForEdit($entity);
                break;
            default:
                throw new \UnexpectedValueException('Unexpected form type');
        }

        $builder->setForm($form);
    }

    /**
     * @param ActionBuilder $builder
     */
    protected function throwExceptionIsNoForm(ActionBuilder $builder)
    {
        if (is_null($builder->getForm())) {
            throw new \RuntimeException('Not found form');
        }
    }

    /**
     * @param ActionBuilder $builder
     * @param Request $request
     */
    public function formHandleRequest(ActionBuilder $builder, Request $request)
    {
        $this->throwExceptionIsNoForm($builder);
        $builder->dispatch(ActionBuilderEvent::EVENT_BEFORE_FORM_BIND, $builder->createEvent());
        $builder->getForm()->handleRequest($request);
        $builder->dispatch(ActionBuilderEvent::EVENT_AFTER_FORM_BIND, $builder->createEvent());
    }

    /**
     * @param ActionBuilder $builder
     * @param ControllerManagerInterface $manager
     */
    public function save(ActionBuilder $builder, ControllerManagerInterface $manager)
    {
        $this->throwExceptionIsNoForm($builder);
        $form = $builder->getForm();

        if ($form->isSubmitted() && $form->isValid()) {
            $builder->dispatch(ActionBuilderEvent::EVENT_BEFORE_SAVE, $builder->createEvent());
            $manager->save($builder->getEntity());
            $builder->dispatch(ActionBuilderEvent::EVENT_AFTER_SAVE, $builder->createEvent());
        }
    }

    /**
     * @return null|Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param null|Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $name
     * @param ActionBuilderEvent $event
     *
     * @return ActionBuilderEvent
     */
    public function dispatch($name, ActionBuilderEvent $event)
    {
        $this->dispatcher->dispatch($name, $event);

        return $event;
    }

    /**
     * @return ActionBuilderEvent
     */
    public function createEvent()
    {
        return new ActionBuilderEvent($this);
    }

    /**
     * @param string $name
     * @param callable $listener
     * @param int $priority
     */
    public function addListener($name, $listener, $priority = 0)
    {
        if (!$this->validEventName($name)) {
            throw new \UnexpectedValueException('Unexpected ActionBuilder event name');
        }

        $this->dispatcher->addListener($name, $listener, $priority);
    }

    /**
     * @param string
     *
     * @return bool
     */
    protected function validEventName($name)
    {
        return in_array($name, $this->allowedEventNames);
    }

    private function generateAllowedEventNames()
    {
        $reflection = new \ReflectionClass(ActionBuilderEvent::class);
        $consts = $reflection->getConstants();

        foreach ($consts as $name => $value) {
            if (strpos($name, 'EVENT_') === 0) {
                $this->allowedEventNames[] = $value;
            }
        }
    }
}
