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

namespace vSymfo\Core\Controller\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Controller
 */
interface WritableInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function storeAction(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request);
}
