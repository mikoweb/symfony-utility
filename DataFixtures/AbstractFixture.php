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

namespace vSymfo\Core\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as BaseFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage DataFixtures
 */
abstract class AbstractFixture extends BaseFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $fixturesDirectory;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->fixturesDirectory = $this->container->get('kernel')->getRootDir() . '/../fixtures/';
    }

    /**
     * @return string
     */
    protected function getFixturesDirectory()
    {
        return $this->fixturesDirectory;
    }

    /**
     * @param string $filename
     *
     * @return \DOMXPath
     *
     * @throws \Exception
     */
    protected function openXmlFile($filename)
    {
        $path = $this->getFixturesDirectory() . $filename;

        if (!file_exists($path)) {
            throw new \Exception('Fixtures file not found.');
        }

        $document = new \DOMDocument();
        $document->loadXML(file_get_contents($path));
        $xpath = new \DOMXPath($document);

        return $xpath;
    }

    /**
     * @param string $class
     * @param string $subclass
     * @return bool
     */
    protected function isSubclassOf($class, $subclass)
    {
        $reflection = new \ReflectionClass($class);
        return $reflection->isSubclassOf($subclass);
    }

    /**
     * @param string $class
     * @param string $subclass
     */
    protected function throwIsNotSubclass($class, $subclass)
    {
        if (!$this->isSubclassOf($class, $subclass)) {
            throw new \UnexpectedValueException($class . ' is not subclass of ' . $subclass);
        }
    }

    /**
     * @param string $class
     * @param string|null $checkIsSubclass
     *
     * @return object
     */
    protected function createEntity($class, $checkIsSubclass = null)
    {
        if (is_string($checkIsSubclass)) {
            $this->throwIsNotSubclass($class, $checkIsSubclass);
        }

        return new $class();
    }
}
