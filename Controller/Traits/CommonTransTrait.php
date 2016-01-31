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

namespace vSymfo\Core\Controller\Traits;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller_Traits
 */
trait CommonTransTrait
{
    /**
     * @return string
     */
    abstract protected function getTransPrefix();

    /**
     * @return string|null
     */
    protected function getTransDomain()
    {
        return null;
    }
}
