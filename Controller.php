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

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use vSymfo\Component\Document\Format\DocumentAbstract;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
class Controller extends SymfonyController
{
    /**
     * @return DocumentAbstract
     */
    public function getDocument()
    {
        return $this->container->get('document');
    }

    /**
     * Renderowanie widoku z zastosowaniem usługi dokumentu
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function renderDocument($view, array $parameters = array(), Response $response = null)
    {
        $response = $this->render($view, $parameters, $response);
        $this->container->get('document')->body($response->getContent());
        $response->setContent($this->container->get('document')->render());

        return $response;
    }

    /**
     * Odpowiedź z wyłączoną dyrektywą cache
     * @param Response|RedirectResponse $response
     * @return Response|RedirectResponse
     */
    public function noCacheResponse($response)
    {   $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }
}
