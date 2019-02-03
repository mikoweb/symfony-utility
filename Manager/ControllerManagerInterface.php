<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Manager;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Common actions. It is usually usage in the controller.
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
    public function getEntityClass(): string;

    /**
     * Returns one entity or throw not found exception.
     *
     * @param Request $request
     * @param string|null $queryKey
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    public function findEntity(Request $request, ?string $queryKey = null);

    /**
     * Gets paginated items.
     *
     * @param Request $request
     * @param integer $limit
     *
     * @return mixed
     */
    public function getPagination(Request $request, int $limit);

    /**
     * Build the form.
     *
     * @param mixed $data
     * @param array $options
     * @param string|null $type
     *
     * @return Form
     */
    public function buildForm($data = null, array $options = [], ?string $type = null): Form;

    /**
     * Get class name of form.
     *
     * @return string
     */
    public function formType(): string;

    /**
     * Save a entity.
     *
     * @param mixed $entity
     *
     * @return void
     */
    public function save($entity): void;

    /**
     * Remove a entity.
     *
     * @param mixed $entity
     *
     * @return void
     */
    public function remove($entity): void;

    /**
     * Check if a entity is correct.
     *
     * @param mixed $entity
     *
     * @return boolean
     */
    public function isRightEntity($entity): bool;
}
