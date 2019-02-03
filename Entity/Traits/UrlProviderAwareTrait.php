<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Traits;

use Mikoweb\SymfonyUtility\Entity\Provider\UrlProviderInterface;

trait UrlProviderAwareTrait
{
    /**
     * @var UrlProviderInterface
     */
    protected $urlProvider;

    /**
     * @param UrlProviderInterface $urlProvider
     */
    public function setUrlProvider(UrlProviderInterface $urlProvider): void
    {
        $this->urlProvider = $urlProvider;
    }
}
