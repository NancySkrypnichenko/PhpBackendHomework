<?php

$sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
// Получаем список всех sql-файлов

foreach (glob("*") as $filename) {
    if (substr($filename, -4) == ".php") {
        continue;
    }
    uniqid($filename);
}