<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as BaseFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    protected function getFixturesDirectory(): string
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
    protected function openXmlFile(string $filename): \DOMXPath
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
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    protected function isSubclassOf(string $class, string $subclass): bool
    {
        $reflection = new \ReflectionClass($class);
        return $reflection->isSubclassOf($subclass);
    }

    /**
     * @param string $class
     * @param string $subclass
     *
     * @throws \ReflectionException
     */
    protected function throwIsNotSubclass(string $class, string $subclass): void
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
     *
     * @throws \ReflectionException
     */
    protected function createEntity(string $class, ?string $checkIsSubclass = null)
    {
        if (is_string($checkIsSubclass)) {
            $this->throwIsNotSubclass($class, $checkIsSubclass);
        }

        return new $class();
    }
}
