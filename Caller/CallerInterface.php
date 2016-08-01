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

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Caller
 */
interface CallerInterface
{
    /**
     * Emulates call of the method.
     *
     * @param object $obj       The associated object.
     * @param string $name      Name of method.
     * @param array $arguments  Method arguments.
     *
     * @return void
     *
     * @throws CallerException
     */
    public function call($obj, $name, array $arguments);

    /**
     * Returns the prefix of supported method names.
     * Only letters and numbers.
     *
     * @return string
     */
    public function callPrefix();
}
