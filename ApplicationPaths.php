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

namespace vSymfo\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Ścieżki do katalogów
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
final class ApplicationPaths
{
    /**
     * @const string
     */
    const SLASH = DIRECTORY_SEPARATOR;

    /**
     * Ścieżka do folderu z zasobami
     * @const string
     */
    const WEB_RESOURCES = "/resources";

    /**
     * Ścieżka do folderu z zasobami
     * @const string
     */
    const WEB_THEMES = "/theme";

    /**
     * Ścieżka do folderu z zasobami
     * @const string
     */
    const WEB_WEBUI = "/resources/webui";

    /**
     * Ścieżka do folderu z cache'm motywów graficznych.
     */
    const WEB_CACHE = "/cache";

    /**
     * Ścieżka do folderu z zasobami
     * @const string
     */
    const WEB_WEBUI_ENGINE = "/resources/engine";


    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Ścieżka do katalogu web
     * @var string
     */
    private $webPath = null;

    /**
     * Ścieżki absolutne
     * @var array
     */
    private $absolutePath = array();

    /**
     * Ścieżki w URL
     * @var array
     */
    private $urlPath = array();

    /**
     * @param ContainerInterface $container
     * @param string $webDir
     * @throws \RuntimeException
     */
    public function __construct(ContainerInterface $container, $webDir = '/../web')
    {
        $this->container = $container;
        $this->webPath = realpath($this->container->getParameter('kernel.root_dir') . $webDir);

        if ($this->webPath === false) {
            throw new \RuntimeException('Web directory not found. Please check that you entered the correct path in variable $webDir.');
        }

        $this->absolutePath = array(
            "kernel_root"   => $this->container->getParameter('kernel.root_dir'),
            "kernel_cache"  => $path = $this->container->getParameter('kernel.cache_dir'),
            "web"           => $this->webPath,
            "web_cache"     => $this->webPath . self::WEB_CACHE,
            "web_resources" => $this->webPath . self::WEB_RESOURCES,
            "webui"         => $this->webPath . self::WEB_WEBUI,
            "webui_engine"  => $this->webPath . self::WEB_WEBUI_ENGINE,
        );

        $this->urlPath = array(
            "web_cache"     => self::WEB_CACHE,
            "web_resources" => self::WEB_RESOURCES,
            "webui"         => self::WEB_WEBUI,
            "webui_engine"  => self::WEB_WEBUI_ENGINE
        );
    }

    /**
     * Nazwa aktywnego szablonu
     * @return string
     */
    public function getThemeName()
    {
        return $this->container->get('liip_theme.active_theme')->getName();
    }

    /**
     * Ścieżka url do katalogu /web
     * @return string
     */
    public function getBasePath()
    {
        return $this->container->get('request')->getBasePath();
    }

    /**
     * Absolutna ścieżka dostepu do katalogu
     * @param string $name
     * @return string|false
     */
    public function absolute($name)
    {
        if (isset($this->absolutePath[$name])) {
            return $this->absolutePath[$name];
        } elseif ($name == "web_theme") {
            return $this->webPath . self::WEB_THEMES . '/' . $this->getThemeName();
        }

        return false;
    }

    /**
     * Ścieżka w adresie URL
     * @param string $name
     * @param bool $baseurl
     * @return string|false
     */
    public function url($name, $baseurl = true)
    {
        if (isset($this->urlPath[$name])) {
            if ($baseurl)
                return $this->getBasePath() . $this->urlPath[$name];
            else
                return $this->urlPath[$name];
        } elseif ($name == "web_theme") {
            if ($baseurl)
                return $this->getBasePath() . self::WEB_THEMES . '/' . $this->getThemeName();
            else
                return self::WEB_THEMES . '/' . $this->getThemeName();
        } elseif ($name == "base" || $name == "web") {
            return $this->getBasePath();
        }

        return false;
    }
}
