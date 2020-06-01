<?php
require_once 'headers.php';

// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_name = "id.txt";

    //if file already exist - should take id from it
    if (file_exists($file_name)) {
        $fp = fopen($file_name, "r+");

        // if file already locked should weight
        while (!flock($fp, LOCK_EX)) {
            usleep(5);
        }

        $count = (int)file_get_contents($file_name);
        $count++;
        file_put_contents($file_name, $count);

        //close block file and connection
        flock($fp, LOCK_UN);
        fclose($fp);

    } else {
        //should create file and open it only for writing
        $fp = fopen($file_name, "w");
        flock($fp, LOCK_EX);
        $count = 0;
        $count++;
        file_put_contents($file_name, $count);
    }

    // get information about text for new task
    $json = file_get_contents("php://input");
    $data = json_decode($json);

    //open database
    $base_name = "database.json";
    $open_base = fopen($base_name, "r+");

    //weight if file is already blacked
    while (!flock($open_base, LOCK_EX)) {
        usleep(5);
    }

    //write in file new task
    $json = json_decode(file_get_contents($base_name), true);
    $json{"items"}[] = array(
        "id" => $count,
        "text" => $data->{'text'},
        "checked" => "true"
    );
    file_put_contents("database.json", json_encode($json));

    //close block database and connection
    flock($open_base, LOCK_UN);
    fclose($open_base);

    //return answer
    echo json_encode(array("id" => $count));
}
