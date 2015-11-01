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

namespace vSymfo\Core\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use vSymfo\Component\Document\Format\DocumentAbstract;

/**
 * Kontroler posiadający renderowanie dokumentu
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Traits
 */
trait DocumentableControllerTrait
{
    /**
     * @return DocumentAbstract
     */
    public function getDocument()
    {
        return $this->container->get('document');
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function renderDocumentResponse(Response $response)
    {
        $this->container->get('document')->body($response->getContent());
        $response->setContent($this->container->get('document')->render());

        return $response;
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

        return $this->renderDocumentResponse($response);
    }
}
