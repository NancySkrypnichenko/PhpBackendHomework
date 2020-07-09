<?php

$sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
// Получаем список всех sql-файлов
// Находим папку с картинками
$sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
// Получаем список всех файлов

foreach (glob($sqlFolder . '*.*') as $filename) {
    echo $filename;

    if (substr($filename, -4) == ".php") {//should leave it here
        continue;
    }

    unlink($filename);
}