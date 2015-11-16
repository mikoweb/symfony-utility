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

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Manager
 */
interface ControllerManagerInterface
{
    /**
     * @return mixed
     */
    public function createEntity();

    /**
     * @param $data = null
     * @param array $options
     *
     * @return Form
     */
    public function buildForm($data = null, array $options = []);

    /**
     * @param $data = null
     *
     * @return Form
     */
    public function buildFormForNew($data = null);

    /**
     * @param $data
     *
     * @return Form
     */
    public function buildFormForEdit($data);

    /**
     * @param mixed $entity
     *
     * @return void
     */
    public function save($entity);

    /**
     * @param mixed $entity
     *
     * @return void
     */
    public function remove($entity);

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function isRightEntity($entity);
}
