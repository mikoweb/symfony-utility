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

namespace vSymfo\Controller;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use vSymfo\Core\Event\ActionBuilderEvent;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller
 */
class ActionBuilder
{
    const EVENT_BEFORE_HANDLE_REQUEST = 'before_handle_request';
    const EVENT_AFTER_HANDLE_REQUEST = 'after_handle_request';
    const BEFORE_SAVE = 'before_save';
    const AFTER_SAVE = 'after_save';

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Form|null
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $entity;

    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->form = null;
        $this->entity = null;
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
     * @param string $eventName
     * @param ActionBuilderEvent $event
     *
     * @return ActionBuilderEvent
     */
    public function dispatch($eventName, ActionBuilderEvent $event)
    {
        $this->eventDispatcher->dispatch($eventName, $event);

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
     * @param string $eventName
     * @param callable $listener
     * @param int $priority
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);
    }
}
