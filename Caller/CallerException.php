<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Caller;

use Exception;

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
    public function getCallData(): array
    {
        return $this->callData;
    }

    /**
     * @param array $callData
     */
    public function setCallData(array $callData)
    {
        $this->callData = $callData;
    }
}
