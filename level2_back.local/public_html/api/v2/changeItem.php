<?php
require_once 'headers.php';
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    //get information from input json
    $json = json_decode(file_get_contents("php://input"));
    $current_id = (int)$json->{'id'};
    $current_text = clean_input_text($json->{'text'});
    $checked_status = $json->{'checked'} ? 1 : 0;

    // get connect with database
    $connect = connect();
    $change_data = mysqli_query($connect, "UPDATE ToDo SET text ='$current_text', checked = '$checked_status' WHERE id = '$current_id'") or report_about_error_database($connect);
    mysqli_close($connect);

    echo json_encode(array("ok" => true));
}
