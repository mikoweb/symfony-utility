<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Interfaces;

use Mikoweb\SymfonyUtility\Entity\Provider\RendererProviderInterface;

interface RendererProviderAwareInterface
{
    /**
     * @param RendererProviderInterface $rendererProvider
     */
    public function setRendererProvider(RendererProviderInterface $rendererProvider): void;
}
