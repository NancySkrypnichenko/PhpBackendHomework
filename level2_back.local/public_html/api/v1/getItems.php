<?php
require_once 'headers.php';

$base_name = "database.json";
$open_base = fopen($base_name, "r+");
try {
    // if one ore more "doto" already was added - file exist. In other way should create it.
    $already_existing_items = file_get_contents($file_name);
} catch (Exeption $exception) {
    $already_existing_items = $json_by_default = "{items:[]}";
    file_put_contents($file_name, $json_by_default);
}

//close block database and connection
flock($open_base, LOCK_UN);
fclose($open_base);

echo $already_existing_items;
