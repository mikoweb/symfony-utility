<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Crud;

use Symfony\Component\EventDispatcher\Event;

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
    public function getData(): DataInterface
    {
        return $this->data;
    }
}
