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

namespace vSymfo\Core\File\Interfaces;

use vSymfo\Core\File\CombineFilesCacheDB;

/**
 * Interfejs złączania plików
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage File_Interfaces
 */
interface CombineFilesInterface
{
    /**
     * @param array $sources
     */
    public function __construct(array $sources = array());

    /**
     * Dodaj ścieżke pliku na listę
     * @param string $path
     * @return CombineFilesInterface $this
     */
    public function addSource($path);

    /**
     * Zwraca ścieżkę do wygenerowanego pliku.
     * @param bool $url
     * @return string
     */
    public function getPath($url = false);

    /**
     * Zwraca zawartość wygenerowanego pliku.
     * Jeśli plik nie istnieje zwraca false.
     * @return bool|string
     */
    public function getContent();

    /**
     * Łączenie plików w całość
     */
    public function combine();

    /**
     * Ustaw ścieżke katalogu wejściowego
     * @param string $dir
     * @return $this
     */
    public function setInputDir($dir);

    /**
     * Ustaw ścieżke katalogu wyjściowego
     * @param string $dir
     * @return $this
     */
    public function setOutputDir($dir);

    /**
     * Ustaw URL katalogu wyjściowego
     * @param string $base
     * @return $this
     */
    public function setOutputBaseUrl($base);

    /**
     * Ustaw nazwe pliku wyjściowego
     * @param string $filename
     * @return $this
     */
    public function setOutputFileName($filename);

    /**
     * Ustaw rozszerzenie pliku wyjściowego
     * @param string $ext
     * @return $this
     */
    public function setOutputExtension($ext);

    /**
     * Ustaw strategie generowania nazwy pliku wyjściowego
     * @param $strategy
     * @return $this
     */
    public function setOutputStrategy($strategy);

    /**
     * Ustaw czas przechowywania wygenerowanych plików (sekundy).
     * @param integer $time
     * @return $this
     */
    public function setOutputLifeTime($time);

    /**
     * Czy generować pliki za każdym razem?
     * @param boolean $force
     * @return $this
     */
    public function setOutputForceRefresh($force);

    /**
     * Ustaw parametry cache'u
     * @param CombineFilesCacheDB $cacheDb
     * @return $this
     */
    public function setCacheDb(CombineFilesCacheDB $cacheDb);
}
