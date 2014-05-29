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

namespace vSymfo\Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as SymfonyExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
abstract class ExtensionAbstract extends SymfonyExtension
{
    /**
     * ustaw parametr
     * @param ContainerBuilder $container
     * @param array $config
     * @param string $key
     */
    protected function setParameter(ContainerBuilder $container, array $config, $key)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $container->setParameter(
            $this->getAlias() . '.' . substr(str_replace(array('[', ']'), array('', '.'), $key), 0, -1),
            $accessor->getValue($config, $key)
        );
    }

    /**
     * uwstaw wszystkie parametry z tablicy
     * @param ContainerBuilder $container
     * @param array $config
     * @param string $root
     */
    protected function setParameterAll(ContainerBuilder $container, array $config, $root = '')
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $values = empty($root)
            ? $config
            : $accessor->getValue($config, $root);

        if (is_array($values)) {
            foreach ($values as $k=>&$v) {
                $this->setParameter($container, $config, $root . '[' . $k . ']');
            }
        }
    }
}
