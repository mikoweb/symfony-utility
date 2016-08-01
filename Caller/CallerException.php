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

namespace vSymfo\Core\Caller;

use Exception;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Caller
 */
class CallerException extends Exception
{
    /**
     * @var mixed
     */
    private $return;

    /**
     * @var array
     */
    private $callData;

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }

    /**
     * @return array
     */
    public function getCallData()
    {
        return $this->callData;
    }

    /**
     * @param array $callData
     */
    public function setCallData($callData)
    {
        $this->callData = $callData;
    }
}
