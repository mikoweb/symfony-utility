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
 * Data returned by the methods of CRUD.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
 */
class Data implements DataInterface
{
    /**
     * @var Form|null
     */
    protected $form;

    /**
     * @var Response|null
     */
    protected $response;

    /**
     * @var object|null
     */
    protected $entity;

    /**
     * @var mixed
     */
    protected $collection;

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(Form $form = null)
    {
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(Response $response = null)
    {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntity($entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * {@inheritdoc}
     */
    public function setCollection($collection = null)
    {
        $this->collection = $collection;
    }
}
