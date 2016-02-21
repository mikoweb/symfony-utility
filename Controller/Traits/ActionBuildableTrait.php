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
use vSymfo\Core\Event\ActionBuilderEvent;

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
     * @param Request $request
     * @param string $formType
     * @param mixed $entity
     *
     * @return ActionBuilder
     */
    public function createActionBuilder(Request $request, $formType, $entity = null)
    {
        $manager = $this->getManager();
        $builder = new ActionBuilder();
        $builder->createForm($builder, $formType, $manager, $entity);
        $builder->formHandleRequest($builder, $request);

        return $builder;
    }

    /**
     * @param ActionBuilder $builder
     * @param string $message
     * @param string $type
     */
    public function addFlashAfterSave(ActionBuilder $builder, $message, $type = 'success')
    {
        $builder->addListener(ActionBuilderEvent::EVENT_AFTER_SAVE, function () use($message, $type) {
            $this->addFlash($type,
                $this->get('translator')->trans(
                    $this->getTransPrefix() . '.' . $message,
                    [], $this->getTransDomain()
                )
            );
        });
    }
}
