<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Controller\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
