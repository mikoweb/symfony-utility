<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Crud;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * CRUD (create, read, update and delete) interface.
 */
interface CrudInterface extends ContainerAwareInterface
{
    /**
     * Return a collection of entities.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     *
     * @throws NotFoundHttpException
     */
    public function index(Request $request, array $options = []): DataInterface;

    /**
     * Route to index.
     *
     * @return string
     */
    public function indexRoute(): string;

    /**
     * Return form for creating a entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     */
    public function create(Request $request, array $options = []): DataInterface;

    /**
     * Route to create.
     *
     * @return string
     */
    public function createRoute(): string;

    /**
     * Create a new entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     */
    public function store(Request $request, array $options = []): DataInterface;

    /**
     * Route to store.
     *
     * @return string
     */
    public function storeRoute(): string;

    /**
     * Return a specific entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     *
     * @throws NotFoundHttpException
     */
    public function show(Request $request, array $options = []): DataInterface;

    /**
     * Route to show.
     *
     * @return string
     */
    public function showRoute(): string;

    /**
     * Return form for editing a entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     *
     * @throws NotFoundHttpException
     */
    public function edit(Request $request, array $options = []): DataInterface;

    /**
     * Route to edit.
     *
     * @return string
     */
    public function editRoute(): string;

    /**
     * Update a specific entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     *
     * @throws NotFoundHttpException
     */
    public function update(Request $request, array $options = []): DataInterface;

    /**
     * Route to update.
     *
     * @return string
     */
    public function updateRoute(): string;

    /**
     * Delete a specific entity.
     * 
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     *
     * @throws NotFoundHttpException
     */
    public function destroy(Request $request, array $options = []): DataInterface;

    /**
     * Route to destroy.
     *
     * @return string
     */
    public function destroyRoute(): string;

    /**
     * Returns object related with CRUD.
     *
     * @return CrudableInterface
     */
    public function getRelated(): CrudableInterface;

    /**
     * Set object related with CRUD.
     *
     * @param CrudableInterface $related
     */
    public function setRelated(CrudableInterface $related): void;
}
