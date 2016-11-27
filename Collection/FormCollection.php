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

namespace vSymfo\Core\Collection;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Collection
 */
class FormCollection extends AbstractLazyCollection
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var array
     */
    protected $defaultFormOptions;

    /**
     * @param FormFactory $formFactory  The Form Factory.
     * @param string $formType          The fully qualified class name of the form type.
     * @param Collection $dataItems     Array of initial data for the form.
     * @param array $defaultFormOptions Default options for the form.
     */
    public function __construct(
        FormFactory $formFactory,
        $formType,
        Collection $dataItems = null,
        array $defaultFormOptions = []
    ) {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->defaultFormOptions = $defaultFormOptions;

        if ($dataItems) {
            foreach ($dataItems as $data) {
                $this->addForm($data);
            }
        }
    }

    /**
     * Add Form instance to collection.
     *
     * @param mixed $data         The initial data for the form.
     * @param array|null $options Options for the form.
     *
     * @return Form|false
     */
    public function addForm($data = null, array $options = null)
    {
        $form = $this->createForm($data, $options);

        return $this->add($form) ? $form : false;
    }

    /**
     * Remove last element.
     *
     * @return boolean
     */
    public function removeLast()
    {
        return $this->removeElement($this->last());
    }

    /**
     * Creates a views.
     *
     * @param FormView $parent The parent view.
     *
     * @return FormView[]|Collection Views.
     */
    public function toViews(FormView $parent = null)
    {
        $views = new ArrayCollection();
        foreach ($this->collection as $k => $form) {
            $view = $form->createView($parent);
            $this->transformIds($view, (string) $k);
            $this->transformName($view, (string) $k);
            $views->add($view);
        }

        return $views;
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        $this->throwIfNotForm($element);
        return parent::add($element);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->throwIfNotForm($value);
        parent::set($key, $value);
    }

    /**
     * Return prototype item for dynamic forms.
     *
     * @return FormView
     */
    public function createPrototypeItem()
    {
        $view = $this->createForm()->createView();
        $this->transformName($view, '__name__');
        $this->transformIds($view, '__name__');

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    protected function doInitialize()
    {
        $this->collection = new ArrayCollection();
    }

    /**
     * @param FormView $view
     * @param string|null $suffix
     */
    protected function transformIds(FormView $view, $suffix)
    {
        if (isset($view->vars['id'])) {
            $view->vars['id'] .= '_' . $suffix;
        }

        foreach ($view->children as $child) {
            $this->transformIds($child, $suffix);
        }
    }

    /**
     * @param FormView $view
     * @param string|null $suffix
     */
    protected function transformName(FormView $view, $suffix)
    {
        if (isset($view->vars['name'])) {
            $view->vars['name'] .= '_' . $suffix;
        }

        if (isset($view->vars['full_name'])) {
            $view->vars['full_name'] .= '_' . $suffix;
        }
    }

    /**
     * @param mixed $data
     * @param array|null $options
     *
     * @return Form
     */
    protected function createForm($data = null, array $options = null)
    {
        return $this->formFactory
            ->createBuilder($this->formType, $data, is_array($options) ? $options : $this->defaultFormOptions)
            ->getForm();
    }

    /**
     * @param $value
     */
    private function throwIfNotForm($value)
    {
        if (!($value instanceof Form)) {
            throw new \UnexpectedValueException('Value is not Form!');
        }
    }
}
