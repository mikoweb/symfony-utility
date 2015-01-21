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

/**
 * Baza danych przechowująca informacje o pamięci podręcznej złączonych plików
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage File
 */
class CombineFilesCacheDB extends \SQLite3
{
    /**
     * Tablica z listą instancji.
     * Indeksy tablicy są ścieżkami do plików bazy danych.
     * @var array
     */
    protected static $instance = array();

    /**
     * Nazwa tabeli
     * @var string
     */
    protected static $tableName = 'cache';

    /**
     * Czy rozpoczęto transakcję?
     * @var bool
     */
    protected $transactionBegin = false;

    /**
     * Pusty konstruktor
     */
    public function __construct()
    {}

    /**
     * Otwiera bazę danych.
     * Sprawdza czy baza jest już otworzona.
     * Jeśli nie zostanie utworzony nowy obiekt.
     *
     * @param string $filename
     * @return CombineFilesCacheDB
     * @throws \LogicException
     */
    public static function openFile($filename)
    {
        if (!isset(self::$instance[$filename])) {
            $dir = dirname($filename);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $db = new CombineFilesCacheDB($filename);
            $db->open($filename);
            $db->exec("PRAGMA synchronous = off; PRAGMA count_changes = off; PRAGMA temp_store = MEMORY;");
            self::$instance[$filename] = $db;

            // sprawdzanie struktury tabeli
            $results = @$db->query('pragma table_info(' . static::$tableName . ');');
            $test = array(
                array(
                    'name' => 'filepath',
                    'type' => 'text'
                ),
                array(
                    'name' => 'serialize',
                    'type' => 'text'
                ),
            );
            $i = 0;
            $del = false;
            while ($row = $results->fetchArray()) {
                if (isset($test[$i])) {
                    // jeśli nazwa kolumny lub typ jest inna niż w tablicy test oznacz tabele do usunięcia
                    if ($row['name'] != $test[$i]['name'] || $row['type'] != $test[$i]['type']) {
                        $del = true;
                    }
                }
                $i++;
            }
            if ($del) {
                $db->exec('DROP TABLE IF EXISTS ' . static::$tableName . ';');
            }

            // tworzenie tabeli jeśli nie istnieje
            if (!$db->exec(
                'CREATE TABLE IF NOT EXISTS ' . static::$tableName . '
                (
                    filepath text NOT NULL UNIQUE,
                    serialize text NOT NULL,
                    PRIMARY KEY (filepath)
                );')
            ) {
                throw new \LogicException('create ' . static::$tableName . ' table failed');
            }
        }

        return self::$instance[$filename];
    }

    /**
     * Serializacja danych
     * @param array $array
     * @return string
     */
    protected function serialize(array $array)
    {
        return serialize(
            array_merge(
                array(
                    'files' => array(),
                    'files_checksum' => '',
                    'generate_time' => 0,
                ),
                $array
            )
        );
    }

    /**
     * @param string $filepath
     * @return array|bool
     */
    public function select($filepath)
    {
        $results = $this->query(
            "SELECT filepath, serialize FROM " . static::$tableName . " WHERE filepath='$filepath' LIMIT 1"
        );
        $row = $results->fetchArray();
        if (!empty($row)) {
            return array(
                'filepath' => $row['filepath'],
                'data'     => unserialize($row['serialize']),
            );
        } else {
            return false;
        }
    }

    /**
     * @param string $filepath
     * @param array $serialize
     * @return bool
     */
    public function insert($filepath, array $serialize)
    {
        return @$this->exec(
            "INSERT INTO " . static::$tableName . "(filepath, serialize) VALUES ('$filepath', '" . $this->serialize($serialize) . "');"
        );
    }

    /**
     * @param string $filepath
     * @param array $serialize
     * @return bool
     */
    public function update($filepath, array $serialize)
    {
        return $this->exec(
            "UPDATE " . static::$tableName . " SET serialize='" . $this->serialize($serialize) . "' WHERE filepath='$filepath';"
        );
    }

    /**
     * @param string $filepath
     * @return bool
     */
    public function delete($filepath)
    {
        return $this->exec("DELETE FROM " . static::$tableName . " WHERE filepath='$filepath';");
    }

    /**
     * Rozpocznij transakcję
     */
    public function beginTransaction()
    {
        if (!$this->transactionBegin) {
            $this->exec('BEGIN TRANSACTION;');
            $this->transactionBegin = true;
        }
    }

    /**
     * Zacznij transakcję
     */
    public function endTransaction()
    {
        if ($this->transactionBegin) {
            $this->exec('END TRANSACTION;');
            $this->transactionBegin = false;
        }
    }

    public function __destruct()
    {
        $this->endTransaction();
        $this->close();
    }
}
