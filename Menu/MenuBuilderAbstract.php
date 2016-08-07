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

namespace vSymfo\Core\Menu;

use JMS\I18nRoutingBundle\Router\I18nRouter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Menu
 */
abstract class MenuBuilderAbstract
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var \SimpleXMLElement
     */
    protected $menuData;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param FactoryInterface $factory
     * @param AuthorizationChecker $authorizationChecker
     * @param TranslatorInterface $translator
     * @param RequestStack $request
     * @param RouterInterface $router
     */
    public function __construct(
        FactoryInterface $factory, 
        AuthorizationChecker $authorizationChecker,
        TranslatorInterface $translator,
        RequestStack $request,
        RouterInterface $router
    ) {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->menuData = null;
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * @return ItemInterface
     */
    abstract function createMenu();

    /**
     * @param string $filename
     *
     * @return ItemInterface
     */
    protected function loadMenuFromXml($filename)
    {
        if (is_null($this->menuData)) {
            $this->menuData = simplexml_load_file($filename);
        }

        $menu = $this->factory->createItem('root');

        foreach ($this->menuData->item as $item) {
            $this->menuItems($menu, $item);
        }

        return $menu;
    }

    /**
     * @param ItemInterface $parent
     * @param \SimpleXMLElement $item
     */
    protected function menuItems(ItemInterface $parent, \SimpleXMLElement $item)
    {
        $route = isset($item['route']) ? (string)$item['route'] : null;
        $title = isset($item['title']) ? (string)$item['title'] : null;

        if (is_null($title) && !is_null($route)) {
            $title = 'routes.' . $route;
        }

        $menu = $this->menuItem(
            $parent,
            $title,
            isset($item['role']) ? (string)$item['role'] : null,
            $route,
            isset($item['uri']) ? (string)$item['uri'] : null,
            isset($item['icon-class']) ? (string)$item['icon-class'] : null,
            $item
        );

        if ($menu) {
            foreach ($item->item as $node) {
                $this->menuItems($menu, $node);
            }
        }
    }

    /**
     * @param ItemInterface $parent
     * @param null|string $role
     * @param null|string $title
     * @param null|string $route
     * @param null|string $uri
     * @param null|string $iconClass
     * @param \SimpleXMLElement $params
     *
     * @return ItemInterface|null
     */
    protected function menuItem(ItemInterface $parent, $title, $role = null, $route = null, $uri = null, $iconClass = null, \SimpleXMLElement $params)
    {
        $item = null;

        if (is_null($role) || $this->authorizationChecker->isGranted($role)) {
            $options = ['label' => $this->translator->trans($title, [], $this->translationDomain())];
            $name = uniqid();
            $hidden = isset($params['hidden']) && (string) $params['hidden'] === 'true';

            if ($hidden) {
                $options['attributes'] = [
                    'style' => 'display: none;',
                    'aria-hidden' => 'true',
                ];
            }

            if ($route) {
                $options['route'] = $route;
                $name = $route . '_' . $name;
                $this->addRouteParameters($route, $params, $hidden, $options);
            }

            if (is_null($route) && $uri) {
                $options['uri'] = $uri;
                $name = $uri . '_' . $name;
            }

            $item = $parent->addChild($name, $options);
            $item->setExtra('hidden', $hidden);

            if ($iconClass) {
                $item->setExtra('icon_class', $iconClass);
            }
        }

        return $item;
    }

    /**
     * @param string $route
     * @param \SimpleXMLElement $params
     * @param boolean $hidden
     * @param array $options
     */
    protected function addRouteParameters($route, \SimpleXMLElement $params, $hidden, array &$options)
    {
        $options['routeParameters'] = [];
        $parameters = $params->xpath('param');

        if (!empty($parameters)) {
            foreach ($parameters as $param) {
                if (isset($param['name'])) {
                    $options['routeParameters'][(string) $param['name']] = (string) $param;
                }
            }
        }

        if ($hidden) {
            if ($this->router instanceof I18nRouter) {
                $collection = $this->router->getOriginalRouteCollection();
            } else {
                $collection = $this->router->getRouteCollection();
            }

            $route = $collection->get($route);
            $variables = [];
            preg_match_all('#\{\w+\}#', $route->getPath(), $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($matches as $match) {
                $variables[] = substr($match[0][0], 1, -1);
            }

            if (!empty($variables)) {
                $defaults = $route->getDefaults();
                foreach ($variables as $variable) {
                    if (!isset($options['routeParameters'][$variable]) && !isset($defaults[$variable])) {
                        $options['routeParameters'][$variable] = $this->request->getCurrentRequest()->get($variable, '0');
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    abstract protected function translationDomain();
}
