<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Traits;

use Mikoweb\SymfonyUtility\Entity\Provider\RendererProviderInterface;

trait RendererProviderAwareTrait
{
    /**
     * @var RendererProviderInterface
     */
    protected $rendererProvider;

    /**
     * @param RendererProviderInterface $rendererProvider
     */
    public function setRendererProvider(RendererProviderInterface $rendererProvider): void
    {
        $this->rendererProvider = $rendererProvider;
    }
}
