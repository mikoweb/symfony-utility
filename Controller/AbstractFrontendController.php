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

namespace vSymfo\Core\Controller;

use Symfony\Component\HttpFoundation\Request;
use vSymfo\Core\Controller;
use vSymfo\Core\Controller\Traits\ActionBuildableTrait;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller
 */
abstract class AbstractFrontendController extends Controller
{
    use ActionBuildableTrait;
}
