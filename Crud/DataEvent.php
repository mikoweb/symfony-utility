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

namespace vSymfo\Core\Crud;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
 */
class DataEvent extends Event
{
    /**
     * Event is triggering before submit form.
     */
    const SUBMIT_BEFORE = 'submit.before';

    /**
     * Event is triggering after submit form.
     */
    const SUBMIT_AFTER = 'submit.after';

    /**
     * Event is triggering before save entity.
     */
    const SAVE_BEFORE = 'save.before';

    /**
     * Event is triggering after save entity.
     */
    const SAVE_AFTER = 'save.after';

    /**
     * @var DataInterface
     */
    protected $data;

    /**
     * @param DataInterface $data
     */
    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    /**
     * @return DataInterface
     */
    public function getData()
    {
        return $this->data;
    }
}
