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

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * CRUD (create, read, update and delete) interface.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Crud
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
    public function index(Request $request, array $options = []);

    /**
     * Return form for creating a entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     */
    public function create(Request $request, array $options = []);

    /**
     * Create a new entity.
     *
     * @param Request $request
     * @param array $options
     *
     * @return DataInterface
     */
    public function store(Request $request, array $options = []);

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
    public function show(Request $request, array $options = []);

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
    public function edit(Request $request, array $options = []);

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
    public function update(Request $request, array $options = []);

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
    public function destroy(Request $request, array $options = []);

    /**
     * Returns object related with CRUD.
     *
     * @return CrudableInterface
     */
    public function getRelated();

    /**
     * Set object related with CRUD.
     *
     * @param CrudableInterface $related
     */
    public function setRelated(CrudableInterface $related);
}
