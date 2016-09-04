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

namespace vSymfo\Core\File;

use vSymfo\Core\File\Interfaces\CombineFilesInterface;

/**
 * Abstrakcja złączania plików
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage File
 */
abstract class CombineFilesAbstract implements CombineFilesInterface
{
    /**
     * Lista plików źródłowych
     * @var array
     */
    protected $sources = array();

    /**
     * Ścieżka katalogu wejściowego
     * @var string
     */
    protected $inputDir = '';

    /**
     * Ścieżka katalogu wyjściowego
     * @var string
     */
    protected $outputDir = '';

    /**
     * URL katalogu wyjściowego
     * @var string
     */
    protected $outputBaseUrl = '';

    /**
     * Nazwa pliku wyjściowego
     * @var string
     */
    protected $outputFileName = '';

    /**
     * Rozszerzenie pliku wyjściowego
     * @var string
     */
    protected $outputExtension = '';


    /**
     * Strategia generowania nazwy pliku wyjściowego.
     * Domyślnie manualnie.
     * Obsługiwane wartość {auto, manual}
     * @var string
     */
    protected $outputStrategy = 'manual';

    /**
     * Pamięć podręczna do łączenia plików
     * @var null|CombineFilesCacheDB
     */
    protected $cacheDb = null;

    /**
     * Czas przechowywania wygenerowanych plików (sekundy).
     * Wartość 0 oznacza, że plik nie będzie odświeżany.
     * @var int
     */
    protected $outputLifeTime = 0;

    /**
     * Generowanie plików za każdym razem.
     * Domyślnie wyłączone.
     * @var bool
     */
    protected $outputForceRefresh = false;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @param array $sources
     */
    public function __construct(array $sources = array())
    {
        foreach ($sources as &$source) {
            $this->addSource($source);
        }
    }

    /**
     * Walidacja ścieżki
     * @param string $path
     * @return int
     */
    private function validPath($path)
    {
        if (is_string($path))
            return preg_match('/^[^*"<>|]*$/', $path);
        else
            return false;
    }

    /**
     * Dodaj ścieżke pliku na listę
     * @param string $path
     * @return CombineFilesInterface $this
     */
    public function addSource($path)
    {
        /*if (!$this->validPath($path))
            throw new \InvalidArgumentException('Invalid source path: '.(string)$path);*/

        $this->sources[] = $path;

        return $this;
    }

    /**
     * Ustaw ścieżke katalogu wejściowego
     * @param string $dir
     * @return CombineFilesAbstract
     * @throws \InvalidArgumentException
     */
    public function setInputDir($dir)
    {
        if (!$this->validPath($dir))
            throw new \InvalidArgumentException('Invalid directory path: '.(string)$dir);

        $this->inputDir = $dir;

        return $this;
    }

    /**
     * Ustaw ścieżke katalogu wyjściowego
     * @param string $dir
     * @return CombineFilesAbstract
     * @throws \InvalidArgumentException
     */
    public function setOutputDir($dir)
    {
        if (!$this->validPath($dir))
            throw new \InvalidArgumentException('Invalid directory path: '.(string)$dir);

        $this->outputDir = $dir;

        return $this;
    }

    /**
     * Ustaw URL katalogu wyjściowego
     * @param string $base
     * @return CombineFilesAbstract
     */
    public function setOutputBaseUrl($base)
    {
        if (is_string($base)) {
            $this->outputBaseUrl = $base;
        }

        return $this;
    }

    /**
     * Ustaw nazwe pliku wyjściowego
     * @param string $filename
     * @return CombineFilesAbstract
     * @throws \InvalidArgumentException
     */
    public function setOutputFileName($filename)
    {
        if (!$this->validPath($filename))
            throw new \InvalidArgumentException('Invalid filename: '.(string)$filename);

        $this->outputFileName = $filename;

        return $this;
    }

    /**
     * Ustaw rozszerzenie pliku wyjściowego
     * @param string $ext
     * @return CombineFilesAbstract
     * @throws \InvalidArgumentException
     */
    public function setOutputExtension($ext)
    {
        if (!is_string($ext) || (is_string($ext) && !preg_match('/^[a-zA-Z]*$/', $ext)))
            throw new \InvalidArgumentException('Invalid file extension: '.(string)$ext);

        $this->outputExtension = '.' . $ext;

        return $this;
    }

    /**
     * Ustaw strategie generowania nazwy pliku wyjściowego
     * @param $strategy
     * @return CombineFilesAbstract
     */
    public function setOutputStrategy($strategy)
    {
        switch ($strategy) {
            case 'auto':
                $this->outputStrategy = 'auto';
                break;
            case 'manual':
            default:
                $this->outputStrategy = 'manual';
        }

        return $this;
    }

    /**
     * Ustaw czas przechowywania wygenerowanych plików (sekundy).
     * @param integer $time
     * @return CombineFilesAbstract
     */
    public function setOutputLifeTime($time)
    {
        if (is_integer($time) && $time > -1) {
            $this->outputLifeTime = $time;
        } else {
            $this->outputLifeTime = 0;
        }

        return $this;
    }

    /**
     * Czy generować pliki za każdym razem?
     * @param boolean $force
     * @return CombineFilesAbstract
     */
    public function setOutputForceRefresh($force)
    {
        if (is_bool($force)) {
            $this->outputForceRefresh = $force;
        } else {
            $this->outputForceRefresh = false;
        }

        return $this;
    }

    /**
     * Ustaw parametry cache'u
     * @param CombineFilesCacheDB $cacheDb
     * @return CombineFilesAbstract
     */
    public function setCacheDb(CombineFilesCacheDB $cacheDb)
    {
        $this->cacheDb = $cacheDb;
        // rozpocznij transakcję w bazie pamięci podręcznej
        $this->cacheDb->beginTransaction();

        return $this;
    }

    /**
     * Tworzy nazwę pliku wyjściowego na podstawie tablicy $this->sources
     * @return string
     */
    private function buildOutputFileName()
    {
        $concate = $this->inputDir;
        foreach ($this->sources as &$source)
            $concate .= '[' . $source . ']';

        return md5($concate);
    }

    /**
     * Zwraca ścieżkę do wygenerowanego pliku.
     * @param bool $url
     * @return string
     */
    public function getPath($url = false)
    {
        $filename = null;
        $dir = $url ? $this->outputBaseUrl : $this->outputDir;
        switch ($this->outputStrategy) {
            case 'manual':
                $filename = $dir . '/' . $this->outputFileName . '.' . $this->outputExtension;
                break;
            case 'auto':
                $filename = $dir . '/' . $this->buildOutputFileName() . '.' . $this->outputExtension;
                break;
        }

        return $filename;
    }

    /**
     * Zwraca zawartość wygenerowanego pliku.
     * Jeśli plik nie istnieje zwraca false.
     * @return bool|string
     */
    public function getContent()
    {
        $filaname = $this->getPath();
        if (file_exists($filaname))
            return file_get_contents($filaname);
        else
            return false;
    }

    /**
     * Czy wygenerowany plik jest przedawniony?
     * @return bool
     */
    protected function isExpired()
    {
        $output = $this->getPath();

        // przypuszczalnie na produkcji można sobie wygenerować pliki ręcznie
        if (file_exists($output) && $this->outputLifeTime === 0 && !$this->outputForceRefresh) {
            return false;
        }

        // wymuś odświeżanie pliku
        if (empty($this->cacheDb)) {
            return true;
        } else {
            $cache = $this->cacheDb->select($output);

            // brak danych w pamięci podręcznej
            if ($cache === false && empty($cache['data'])) {
                return true;
            }

            // jeśli brak listy plików
            if (empty($cache['data']['files'])) {
                return true;
            }

            // jeśli czas życia ustawiono na zero
            // to znaczy że plik jest wieczny
            if ($this->outputLifeTime === 0 && !$this->outputForceRefresh) {
                return false;
            } // w przeciwnym razie przeprowadź test źródeł
            else {
                // test na plikach zostanie przeprowadzony...
                // tylko wtedy gdy wygenerowany plik jest przedawniony
                // lub ustawiono wymuszenie odświerzania
                if ($cache['data']['generate_time'] + $this->outputLifeTime < time() || $this->outputForceRefresh) {
                    // oznacz jako przestarzały jeśli daty modyfikacji pliku się nie zgadzają
                    foreach ($cache['data']['files'] as $filename => $modifytime) {
                        if (@filemtime($filename) != $modifytime)
                            return true;
                    }

                    // oznacz jako przestarzały jeśli suma kontrolna się nie zgadza
                    $list = array();
                    foreach ($this->sources as &$source)
                        $list[] = $this->inputDir . $source;
                    if ($cache['data']['files_checksum'] != $this->checksum($list)) {
                        return true;
                    }
                }

                return false;
            }
        }
    }

    /**
     * Łączenie plików w całość
     */
    public function combine()
    {
        if (empty($this->cacheDb)) {
            throw new \Exception('Non setup cacheDb object. First please use setCacheDb() method.');
        }

        // sprawdź czy plik jest przedawniony
        if ($this->isExpired()) {
            $cacheArray = array(
                'files'          => array(),
                'files_checksum' => '',
                'generate_time'  => time()
            );
            $content = '';
            // łączenie plików
            $list = array();
            foreach ($this->sources as $source) {
                $cacheArray['files'][$this->inputDir . $source] = @filemtime($this->inputDir . $source);
                $s = $this->fileGetContents($this->inputDir . $source, $cacheArray['files'], $source);
                $list[] = $this->inputDir . $source;
                $content .= $this->processOneFile($s);
            }
            // generowanie sumy kontrolnej
            $cacheArray['files_checksum'] = $this->checksum($list);

            $output = $this->getPath();
            $dir = dirname($output);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            // zapisywanie pamięci podręcznej
            if ($this->cacheDb->insert($output, $cacheArray) === false) {
                // aktualizacja
                $this->cacheDb->update($output, $cacheArray);
            }
            // zapisywanie wygenerowanej zawartości
            file_put_contents($output, $this->processFiles($content));
        }
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Tworzy sumę kontrolną na podstawie tablicy ścieżek
     * @param array $files
     * @return string
     */
    protected function checksum(array $files)
    {
        $sum = '';
        foreach ($files as &$file) {
            $sum .= '[' . $file . ']';
        }

        return md5($sum);
    }

    /**
     * Przetwórz zawartość pojedynczego pliku
     * @param string $content
     * @return string
     */
    abstract protected function processOneFile(&$content);

    /**
     * Przetwórz zawartość złączonych plików
     * @param string $content
     * @return string
     */
    abstract protected function processFiles(&$content);

    /**
     * Pobieranie zawartości pliku
     * @param string $path
     * @param array $cacheFiles
     * @param string|null $relativePath
     * @return string
     */
    abstract protected function fileGetContents($path, array &$cacheFiles, $relativePath = null);
}
