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
 * Data returned by the methods of CRUD.
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
    public function getForm(): ?Form
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(?Form $form = null): void
    {
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(?Response $response = null): void
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
