<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Caller;

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
    public function call($obj, string $name, array $arguments): void;

    /**
     * Returns the prefix of supported method names.
     * Only letters and numbers.
     *
     * @return string
     */
    public function callPrefix(): string;
}
