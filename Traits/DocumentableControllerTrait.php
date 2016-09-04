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
use vSymfo\Component\Document\Format\HtmlDocument;
use vSymfo\Component\Document\Resources\Interfaces\CombineResourceInterface;

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
        $document = $this->getDocument();
        $document->body($response->getContent());
        $response->setContent($document->render());

        if ($this->container->get( 'kernel' )->getEnvironment() === 'dev') {
            $this->throwResourcesCombineExceptions($document);
        }

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

    /**
     * @param DocumentAbstract $document
     *
     * @throws \Exception
     */
    private function throwResourcesCombineExceptions(DocumentAbstract $document)
    {
        if ($document instanceof HtmlDocument) {
            $resources = array_merge($document->resources('javascript')->resources(),
                $document->resources('stylesheet')->resources());

            foreach ($resources as $resource) {
                if ($resource instanceof CombineResourceInterface
                    && $resource->getCombineObject()->getException() instanceof \Exception
                ) {
                    throw $resource->getCombineObject()->getException();
                }
            }
        }
    }
}
