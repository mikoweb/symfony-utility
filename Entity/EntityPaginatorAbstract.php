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

namespace vSymfo\Core\Entity;

use AshleyDawson\SimplePagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Paginacja dla repozytoriów doctrine
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Entity
 */
abstract class EntityPaginatorAbstract
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var OptionsResolver
     */
    protected $optionsResolver = null;

    /**
     * @var EntityRepository
     */
    private $repository = null;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->optionsResolver = new OptionsResolver();
        $this->setDefaultOptions($this->optionsResolver);
        $this->setOptions($options);
    }

    /**
     * Domyślne opcje
     * @param OptionsResolver $resolver
     */
    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('limit', 'pages_in_range'));
        $resolver->setDefaults(array(
                'limit' => 20,
                'pages_in_range' => 5
            ));

        $resolver->setAllowedTypes(array(
                'limit' => 'integer',
                'pages_in_range' => 'integer'
            ));
    }

    /**
     * Ustawienie opcji
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $this->optionsResolver->resolve($options);
    }

    /**
     * Tworzenie obiektu paginacji
     * @return Paginator
     */
    protected function createPaginator()
    {
        $paginator = new Paginator();
        $paginator
            ->setItemsPerPage($this->options['limit'])
            ->setPagesInRange($this->options['pages_in_range'])
        ;

        return $paginator;
    }

    /**
     * Ustaw obiekt repozytorium
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository)
    {
        if ($this->repositoryValidation($repository)) {
            $this->repository = $repository;
        } else {
            throw new \UnexpectedValueException("Not valid repository.");
        }
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Walidacja repozytorium
     * @param EntityRepository $repository
     * @return bool
     */
    protected abstract function repositoryValidation(EntityRepository $repository);
}
