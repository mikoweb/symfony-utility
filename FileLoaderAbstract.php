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

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 */
abstract class FileLoaderAbstract extends FileLoader
{
    /**
     * Lista przetworzonych plików
     * @var array
     */
    protected static $yaml = array();

    /**
     * Opcje
     *
     * @var array
     */
    protected $options = array();

    /**
     * @param FileLocatorInterface $locator
     * @param array $options
     */
    public function __construct(FileLocatorInterface $locator, array $options)
    {
        parent::__construct($locator);
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Domyślne opcje
     *
     * @param OptionsResolver $resolver
     */
    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('cache_dir'));
        $resolver->setDefaults(array(
            'cache_refresh' => false
        ));

        $resolver->setAllowedTypes('cache_refresh', 'bool');
    }


    /**
     * Odświeżanie danych w cache
     *
     * @param string $filename
     * @param ConfigCache $cache
     * @return void
     */
    abstract protected function refreshCache($filename, ConfigCache $cache);

    /**
     * Zadanie do zrobienia po załadowaniu pliku
     *
     * @param string $filename
     * @param null|string $type
     * @return void
     */
    abstract protected function process($filename, $type = null);

    /**
     * Zapisywanie cache
     *
     * @param ConfigCache $cache
     * @param FileResource $resource
     * @param string $content
     */
    final protected function writeCache(ConfigCache $cache, FileResource $resource, $content)
    {
        /**
         * Może być taka sytuacja, że plik konfiguracyjny jest pusty,
         * jest to dopuszczalne. Jednak do prawidłowego działania klasy
         * potrzebny jest typ tablicowy lub obiektowy. Zatem gdy zmienna
         * $content nie jest tablicą ani obiektem trzeba wartość tej zmiennej
         * zapisać to pierwszego indeksu nowo powstałej tablicy.
         */
        if (!is_array($content) && !is_object($content)) {
            $content = array($content);
        }

        $cache->write('<?php return unserialize('.var_export(serialize($content), true).');', array($resource));
    }

    /**
     * @param string $resource
     * @param null $type
     * @throws \UnexpectedValueException
     */
    final public function load($resource, $type = null)
    {
        if ($this->supports($resource, $type)) {
            $filename = $this->locator->locate($resource);
            // nie otwieraj dwa razy tego samego pliku
            if (empty(self::$yaml[$filename])) {
                $cachePath =
                    $this->options['cache_dir']
                    . '/' . md5($resource . $filename)
                    . '.php';

                $cache = new ConfigCache($cachePath, $this->options['cache_refresh']);
                if (!$cache->isFresh()) {
                    $this->refreshCache($filename, $cache);
                }

                // wczytywanie cache
                self::$yaml[$filename] = require $cachePath;
            }

            $this->process($filename, $type);
        } else {
            throw new \UnexpectedValueException('not supported resource: ' . $resource);
        }
    }

    /**
     * @param string $resource
     * @param null $type
     * @return bool
     */
    final public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }

    /**
     * @param string $optionName
     * @param object $obj
     * @param string $interfaceName
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    final public function compareOptionType($optionName, $obj, $interfaceName)
    {
        if (!is_object($obj)) {
            throw new \InvalidArgumentException('$obj argument is not object type');
        }

        if (!is_string($optionName) || !is_string($interfaceName)) {
            throw new \InvalidArgumentException('$optionName or $interfaceName is not string');
        }

        if (!is_a($obj, $interfaceName)) {
            throw new \UnexpectedValueException('Option ' . $optionName . ' passed to '
                . get_class($obj) . ' must be an instance of ' . $interfaceName);
        }
    }
}
