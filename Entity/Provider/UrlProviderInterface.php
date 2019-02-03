<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Provider;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface UrlProviderInterface
{
    /**
     * @link http://api.symfony.com/3.1/Symfony/Component/Routing/Generator/UrlGeneratorInterface.html
     * @param string $name
     * @param mixed  $parameters
     * @param int    $referenceType
     *
     * @return string
     */
    public function generate(string $name, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);
}
