<?php
session_start();

require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json = json_decode(file_get_contents("php://input"));

    //if session is not exist should report about error
    if (!session_name()) {
        report_about_authorization_error('to perform actions it is necessary to log in');
    }

    //get information about new task
    $userId = (int)$_SESSION['user']['id'];
    $connect = connect();
    $newText = clean_input_text($json->{'text'});

    //try to add task in database
    $add_task = mysqli_query($connect, "INSERT INTO ToDo (user_id, text, checked) VALUES ('$userId', '$newText', '0');") or report_about_error_database($connect);
    echo json_encode(array("id" => mysqli_insert_id($connect)));

    //close connect with database
    mysqli_close($connect);
}
