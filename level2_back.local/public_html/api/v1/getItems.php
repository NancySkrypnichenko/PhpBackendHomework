<?php
session_start();
header("Access-Control-Allow-Origin: http://level2.local");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: text/html; charset=utf-8');

require_once 'connect.php';

//$file_name = "database.json";
//
//try {
//    // if one ore more "doto" already was added - file exist. In other way should create it.
//    $already_existing_items = file_get_contents($file_name);
//} catch (Exeption $exception) {
//    $already_existing_items = $json_by_default = "{items: []}";
//    file_put_contents($file_name, $json_by_default);
//}
//
//echo $already_existing_items;

$login = $_SESSION['user']['id'];

$check_user = mysqli_query($connect, "SELECT * FROM `ToDo` WHERE `user_id` = '$login'");
$number_iteration = mysqli_num_rows($check_user);

$json = array('items' => []);

for ($i = 0; $i < $number_iteration; $i++) {
    $todos = mysqli_fetch_assoc($check_user);

    $json['items'][$i] = $todos;
    if ($json['items'][$i]['checked'] == 1) {
        $json['items'][$i]['checked'] = true;
    } else {
        $json['items'][$i]['checked'] = false;
    }
}
echo json_encode($json);
