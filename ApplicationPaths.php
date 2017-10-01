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

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Application paths.
 *
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
class ApplicationPaths implements ContainerAwareInterface
{
    /**
     * @const string
     */
    const SLASH = DIRECTORY_SEPARATOR;

    /**
     * Directory to web resources.
     *
     * @const string
     */
    const WEB_RESOURCES = "/resources";

    /**
     * Directory to themes.
     *
     * @const string
     */
    const WEB_THEMES = "/theme";

    /**
     * Web cache directory.
     *
     * @const string
     */
    const WEB_CACHE = "/cache";

    /**
     * Directory to WebUI.
     *
     * @const string
     */
    const WEBUI = "/webui";

    /**
     * Directory to WebUI Engine.
     *
     * @const string
     */
    const WEBUI_ENGINE = "/webui/engine";

    /**
     * Directory to node modules.
     *
     * @const string
     */
    const NODE_MODULES = "/node_modules";

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Path to web directory.
     *
     * @var string
     */
    protected $webPath;

    /**
     * Path to private directory.
     *
     * @var string
     */
    protected $privatePath;

    /**
     * Path to node modules directory.
     *
     * @var string
     */
    protected $nodeModulesPath;

    /**
     * Absolute paths.
     *
     * @var array
     */
    protected $absolutePaths;

    /**
     * URL paths.
     *
     * @var array
     */
    protected $urlPaths;

    /**
     * @param string $webDir
     * @param string $privateDir
     */
    public function __construct($webDir = '../web', $privateDir = '../private')
    {
        $this->webPath = $webDir;
        $this->privatePath = $privateDir;
        $this->absolutePaths = [];
        $this->urlPaths = [];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->initializePaths($this->webPath, $this->privatePath);
    }

    /**
     * Returns root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->container->getParameter('kernel.root_dir');
    }

    /**
     * Returns application cache directory.
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->container->getParameter('kernel.cache_dir');
    }

    /**
     * Returns web directory.
     *
     * @return string
     */
    public function getWebDir()
    {
        return $this->webPath;
    }

    /**
     * Returns private directory.
     *
     * @return string
     */
    public function getPrivateDir()
    {
        return $this->privatePath;
    }

    /**
     * Gets name of active theme.
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->container->get('liip_theme.active_theme')->getName();
    }

    /**
     * Returns path to base URL.
     *
     * @return string
     */
    public function getBasePath()
    {
        try {
            $request = $this->container->get('request_stack')->getCurrentRequest();
        } catch (\Exception $e) {
            return '';
        }

        return $request->getBasePath();
    }

    /**
     * Returns path to theme URL exclude base path.
     * 
     * @return string
     */
    public function getThemePath()
    {
        return self::WEB_THEMES . '/' . $this->getThemeName();
    }

    /**
     * Returns absolute path to specific directory.
     *
     * @param string $name Name of directory.
     *
     * @return string
     */
    public function absolute($name)
    {
        if (isset($this->absolutePaths[$name])) {
            return $this->absolutePaths[$name];
        } elseif ($name == 'private_theme') {
            return $this->privatePath . $this->getThemePath();
        } elseif ($name == 'web_theme') {
            return $this->webPath . $this->getThemePath();
        }

        throw new \UnexpectedValueException("Undefined path: '$name'");
    }

    /**
     * Returns URL to specific resource.
     *
     * @param string $name   Name of resource.
     * @param bool $baseUrl  Is contains baseUrl?
     *
     * @return string
     */
    public function url($name, $baseUrl = true)
    {
        if (isset($this->urlPaths[$name])) {
            return $baseUrl ? $this->getBasePath() . $this->urlPaths[$name] : $this->urlPaths[$name];
        } elseif ($name == 'web_theme') {
            return $baseUrl ? $this->getBasePath() . $this->getThemePath() : $this->getThemePath();
        } elseif ($name == 'base' || $name == 'web') {
            return $this->getBasePath();
        }

        throw new \UnexpectedValueException("Undefined path: '$name'");
    }

    /**
     * @param string $webDir
     * @param string $privateDir
     */
    protected function initializePaths($webDir, $privateDir)
    {
        $this->webPath = realpath($this->getRootDir() . '/' . $webDir);
        $this->privatePath = (string) realpath($this->getRootDir() . '/' . $privateDir);
        $this->nodeModulesPath = (string) realpath($this->getRootDir() . '/..' . self::NODE_MODULES);

        if ($this->webPath === false) {
            throw new \RuntimeException('Web directory not found');
        }

        $this->absolutePaths = [
            'kernel_root'       => $this->getRootDir(),
            'kernel_cache'      => $path = $this->getCacheDir(),
            'web'               => $this->webPath,
            'web_cache'         => $this->webPath . self::WEB_CACHE,
            'web_resources'     => $this->webPath . self::WEB_RESOURCES,
            'private'           => $this->privatePath,
            'webui'             => $this->privatePath . self::WEBUI,
            'webui_engine'      => $this->privatePath . self::WEBUI_ENGINE,
            'node_modules'      => $this->nodeModulesPath
        ];

        $this->urlPaths = [
            'web_cache'     => self::WEB_CACHE,
            'web_resources' => self::WEB_RESOURCES
        ];
    }
}
