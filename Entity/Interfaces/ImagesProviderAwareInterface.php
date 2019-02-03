<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Entity\Interfaces;

use Mikoweb\SymfonyUtility\Entity\Provider\ImagesProviderInterface;

interface ImagesProviderAwareInterface
{
    /**
     * @param ImagesProviderInterface $imagesProvider
     */
    public function setImagesProvider(ImagesProviderInterface $imagesProvider): void;
}
