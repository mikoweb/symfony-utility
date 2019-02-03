<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Interfaces;

interface HtmlableInterface
{
    /**
     * Gets as a string of HTML.
     *
     * @return string
     */
    public function toHtml(): string;
}
