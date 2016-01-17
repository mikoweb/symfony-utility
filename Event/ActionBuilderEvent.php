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

namespace vSymfo\Core\Event;

use Symfony\Component\EventDispatcher\Event;
use vSymfo\Controller\ActionBuilder;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Event
 */
class ActionBuilderEvent extends Event
{
    /**
     * @var ActionBuilder
     */
    protected $builder;

    /**
     * @param ActionBuilder $builder
     */
    public function __construct(ActionBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return ActionBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
