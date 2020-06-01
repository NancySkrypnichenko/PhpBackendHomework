<?php
require_once 'headers.php';
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    //get information from input json
    $json = json_decode(file_get_contents("php://input"));
    $id_to_delete = (int)$json->{'id'};

    //delete task from database
    $connect = connect();
    $result = mysqli_query($connect, "DELETE FROM ToDo WHERE id = '$id_to_delete'") or report_about_error_database($connect);
    mysqli_close($connect);

    echo json_encode(array("ok" => true));
}
