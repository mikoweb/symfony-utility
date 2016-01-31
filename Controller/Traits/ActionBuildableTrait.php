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

namespace vSymfo\Core\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;
use vSymfo\Core\Controller\ActionBuilder;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller_Traits
 */
trait ActionBuildableTrait
{
    use ManageableTrait;
    use CommonTransTrait;

    /**
     * @param ActionBuilder $builder
     * @param Request $request
     * @param string $successMessage
     *
     * @return ActionBuilder
     */
    protected function newActionBuilder(ActionBuilder $builder, Request $request, $successMessage = 'success')
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

            $this->addFlash($successMessage,
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
     * @param string $successMessage
     *
     * @return ActionBuilder
     */
    protected function editActionBuilder(ActionBuilder $builder, Request $request, $entity, $successMessage = 'success')
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

            $this->addFlash($successMessage,
                $this->get('translator')->trans(
                    $this->getTransPrefix() . '.messages.save_successful',
                    [], $this->getTransDomain()
                ));

            $builder->dispatch(ActionBuilder::AFTER_SAVE, $builder->createEvent());
        }

        return $builder;
    }
}
