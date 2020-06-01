<?php
require_once 'headers.php';

$json = json_decode(file_get_contents("php://input"));

//open database
$base_name = "database.json";
$open_base = fopen($base_name, "r+");

$json = json_decode(file_get_contents("database.json"), true);
foreach ($json{"items"} as $key => $value) {
    if ($value{"id"} === (int)$json->{'id'}) {
        array_splice($json["items"], $key, 1);
        file_put_contents("database.json", json_encode($json));
        break;
    }
}

//close block database and connection
flock($open_base, LOCK_UN);
fclose($open_base);

echo json_encode(array("ok" => true));
