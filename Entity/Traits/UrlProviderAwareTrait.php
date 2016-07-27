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

namespace vSymfo\Core\Entity\Traits;

use vSymfo\Core\Entity\Provider\UrlProviderInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
trait UrlProviderAwareTrait
{
    /**
     * @var UrlProviderInterface
     */
    protected $urlProvider;

    /**
     * @param UrlProviderInterface $urlProvider
     */
    public function setUrlProvider(UrlProviderInterface $urlProvider)
    {
        $this->urlProvider = $urlProvider;
    }
}
