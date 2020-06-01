<?php
require_once 'headers.php';
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //if session is not exist should report about error
    if (!session_name()) {
        report_about_authorization_error('to perform actions it is necessary to log in');
    }
    $sql = 'INSERT INTO ToDo (user_id, text, checked) VALUES (:userId, :newText, :checked)';

    $userId = (int)$_SESSION['user']['id'];

    //form new task to put it to database
    $json = json_decode(file_get_contents("php://input"));
    $newText = clean_input_text($json->{'text'});
    $query = $pdo_connect->prepare($sql);
    $query->execute(['userId' => $userId, 'newText' => $newText, 'checked' => 0]);

    // return to front id of last task, that was added
    echo json_encode(array("id" => $pdo_connect->lastInsertId()));
}