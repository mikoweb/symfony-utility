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
use vSymfo\Core\Traits\DocumentableControllerTrait;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
class Controller extends SymfonyController
{
    use DocumentableControllerTrait;

    /**
     * {@inheritdoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($this->getViewPrefix() . $view, $parameters, $response);
    }

    /**
     * Odpowiedź z wyłączoną dyrektywą cache
     * 
     * @param Response $response
     * 
     * @return Response
     */
    public function noCacheResponse(Response $response)
    {
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * @return string
     */
    protected function getViewPrefix()
    {
        return '';
    }
}
