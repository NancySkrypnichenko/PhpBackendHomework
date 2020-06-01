<?php
require_once 'headers.php';
//get information from input json
$json = file_get_contents("php://input");
$data = json_decode($json);

// open database
$base_name = "database.json";
$open_base = fopen($base_name, "r+");

//weight if file is already blacked
while (!flock($open_base, LOCK_EX)) {
    usleep(5);
}

//get information from base
$json = json_decode(file_get_contents($base_name), true);

foreach ($json{"items"} as $key => $value) {
    if ($value{"id"} === (int)$data->{'id'}) {
        $json["items"][$key]["text"] = $data->{'text'};
        $json["items"][$key]["checked"] = $data->{'checked'};
        file_put_contents("database.json", json_encode($json));
    }
}

//close block database and connection
flock($open_base, LOCK_UN);
fclose($open_base);

echo json_encode(array("ok" => true));
