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

namespace vSymfo\Core\Crud;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

/**
 * Data returned by the methods of CRUD interface.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
 */
interface DataInterface
{
    /**
     * Return a form intended for render in view.
     *
     * @return Form|null
     */
    public function getForm();

    /**
     * Set a form.
     *
     * @param Form|null $form
     */
    public function setForm(Form $form = null);

    /**
     * Return a response. It is usually used to redirects.
     *
     * @return Response|null
     */
    public function getResponse();

    /**
     * Set a response.
     *
     * @param Response|null $response
     */
    public function setResponse(Response $response = null);

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
