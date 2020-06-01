<?php
session_start();

require_once 'headers.php';
require_once 'connect.php';

$login = (int)$_SESSION['user']['id'];
$json = array('items' => []);

$connect = connect();
$check_user = mysqli_query($connect, "SELECT * FROM `ToDo` WHERE `user_id` = '$login'") or report_about_error_database($connect);
$number_iteration = mysqli_num_rows($check_user);

// after taken all information from base about task of current user should make json as front answer
for ($i = 0; $i < $number_iteration; $i++) {
    $todos = mysqli_fetch_assoc($check_user);
    $json['items'][$i] = $todos;
    $json['items'][$i]['checked'] = $json['items'][$i]['checked'] == 1;
}
mysqli_close($connect);
echo json_encode($json);
