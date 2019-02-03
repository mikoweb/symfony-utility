<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Crud;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

/**
 * Data returned by the methods of CRUD interface.
 */
interface DataInterface
{
    /**
     * Return a form intended for render in view.
     *
     * @return Form|null
     */
    public function getForm(): ?Form;

    /**
     * Set a form.
     *
     * @param Form|null $form
     */
    public function setForm(?Form $form = null): void;

    /**
     * Return a response. It is usually used to redirects.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response;

    /**
     * Set a response.
     *
     * @param Response|null $response
     */
    public function setResponse(?Response $response = null): void;

    /**
     * Return a entity object.
     *
     * @return object|null
     */
    public function getEntity();

    /**
     * Set a entity.
     *
     * @param object|null
     */
    public function setEntity($entity = null);

    /**
     * Return a collection. It is usually set by CrudInterface::index method.
     *
     * @return mixed
     */
    public function getCollection();

    /**
     * Set a collection.
     *
     * @param mixed $collection
     */
    public function setCollection($collection = null);
}
