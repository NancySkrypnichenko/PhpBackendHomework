<?php
header("Access-Control-Allow-Origin: http://level2.local");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: text/html; charset=utf-8');
$file_name = "database.json";

try {
    // if one ore more "doto" already was added - file exist. In other way should create it.
    $already_existing_items = file_get_contents($file_name);
} catch (Exeption $exception) {
    $already_existing_items = $json_by_default = "{items: []}";
    file_put_contents($file_name, $json_by_default);
}

echo $already_existing_items;