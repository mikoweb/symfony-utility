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

namespace vSymfo\Core\Manager;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Common actions. It is usually usage in the controller.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Manager
 */
interface ControllerManagerInterface
{
    /**
     * Gets the new entity object.
     *
     * @param array $args Arguments to pass to a constructor.
     *
     * @return object
     */
    public function createEntity(array $args = []);

    /**
     * Returns name of entity class.
     *
     * @return string
     */
    public function getEntityClass();

    /**
     * Returns one entity or throw not found exception.
     *
     * @param Request $request
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    public function findEntity(Request $request);

    /**
     * Gets paginated items.
     *
     * @param Request $request
     * @param integer $limit
     *
     * @return mixed
     */
    public function getPagination(Request $request, $limit);

    /**
     * Build the form.
     *
     * @param mixed $data
     * @param array $options
     * @param string|null $type
     *
     * @return Form
     */
    public function buildForm($data = null, array $options = [], $type = null);

    /**
     * Get class name of form.
     * 
     * @return string
     */
    public function formType();

    /**
     * Save a entity.
     *
     * @param mixed $entity
     *
     * @return void
     */
    public function save($entity);

    /**
     * Remove a entity.
     *
     * @param mixed $entity
     *
     * @return void
     */
    public function remove($entity);

    /**
     * Check if a entity is correct.
     *
     * @param mixed $entity
     *
     * @return boolean
     */
    public function isRightEntity($entity);
}
