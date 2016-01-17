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

use Symfony\Component\HttpFoundation\Request;
use vSymfo\Core\Controller;
use vSymfo\Core\Manager\ControllerManagerInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller
 */
abstract class AbstractBackendController extends Controller
{
    /**
     * @return ControllerManagerInterface
     */
    abstract protected function getManager();

    /**
     * @return string
     */
    abstract protected function getTransPrefix();

    /**
     * @return string|null
     */
    protected function getTransDomain()
    {
        return null;
    }

    /**
     * @param ActionBuilder $builder
     * @param Request $request
     *
     * @return ActionBuilder
     */
    protected function newActionBuilder(ActionBuilder $builder, Request $request)
    {
        $manager = $this->getManager();
        $entity = $manager->createEntity();
        $builder->setEntity($entity);
        $form = $manager->buildFormForNew($entity);
        $builder->setForm($form);

        $builder->dispatch(ActionBuilder::EVENT_BEFORE_HANDLE_REQUEST, $builder->createEvent());
        $form->handleRequest($request);
        $builder->dispatch(ActionBuilder::EVENT_AFTER_HANDLE_REQUEST, $builder->createEvent());

        if ($form->isSubmitted() && $form->isValid()) {
            $builder->dispatch(ActionBuilder::BEFORE_SAVE, $builder->createEvent());
            $manager->save($entity);

            $this->addFlash('success',
                $this->get('translator')->trans(
                    $this->getTransPrefix() . '.messages.create_successful',
                    [], $this->getTransDomain()
                ));

            $builder->dispatch(ActionBuilder::AFTER_SAVE, $builder->createEvent());
        }

        return $builder;
    }

    /**
     * @param ActionBuilder $builder
     * @param Request $request
     * @param mixed $entity
     *
     * @return ActionBuilder
     */
    protected function editActionBuilder(ActionBuilder $builder, Request $request, $entity)
    {
        $manager = $this->getManager();
        $builder->setEntity($entity);
        $form = $manager->buildFormForEdit($entity);
        $builder->setForm($form);

        $builder->dispatch(ActionBuilder::EVENT_BEFORE_HANDLE_REQUEST, $builder->createEvent());
        $form->handleRequest($request);
        $builder->dispatch(ActionBuilder::EVENT_AFTER_HANDLE_REQUEST, $builder->createEvent());

        if ($form->isSubmitted() && $form->isValid()) {
            $builder->dispatch(ActionBuilder::BEFORE_SAVE, $builder->createEvent());
            $manager->save($entity);

            $this->addFlash('success',
                $this->get('translator')->trans(
                    $this->getTransPrefix() . '.messages.save_successful',
                    [], $this->getTransDomain()
                ));

            $builder->dispatch(ActionBuilder::AFTER_SAVE, $builder->createEvent());
        }

        return $builder;
    }
}
