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

use Stringy\Stringy as S;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Caller
 */
trait HookCallerTrait
{
    /**
     * @param string $name      Name of method.
     * @param array $arguments  Method arguments.
     *
     * @return mixed
     *
     * @throws MultipleCallersException
     */
    public function __call($name, array $arguments)
    {
        $underscored = S::create($name)->underscored();
        $callers = [];
        $callersNames = [];
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            if (is_a($this->{$property->name}, CallerInterface::class)
                && $underscored->indexOf($this->{$property->name}->callPrefix() . '_') === 0
            ) {
                if (!in_array($this->{$property->name}, $callers, true)) {
                    $callers[] = $this->{$property->name};
                    $callersNames[] = $property->name;
                }
            }
        }

        $methodName = get_class($this) . '::' . $name . '()';
        $errorMessage = 'Call to undefined method ' . $methodName . '.';

        if (empty($callers)) {
            trigger_error($errorMessage, E_USER_ERROR);
        }

        if (($count = count($callers)) !== 1) {
            throw new MultipleCallersException("Found $count callers (" . implode(', ', $callersNames) . ") for method $methodName.");
        }

        try {
            $exception = null;
            $callers[0]->call($this, $name, $arguments);
        } catch (CallerException $e) {
            $exception = $e;
        }

        if (is_null($exception)) {
            trigger_error($errorMessage, E_USER_ERROR);
        }

        return $exception->getReturn();
    }
}
