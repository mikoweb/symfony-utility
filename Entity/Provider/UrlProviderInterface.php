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

namespace vSymfo\Core\Entity\Provider;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity_Provider
 */
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
    public function generate($name, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);
}
