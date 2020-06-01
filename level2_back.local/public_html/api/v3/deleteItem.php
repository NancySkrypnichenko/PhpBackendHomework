<?php
require_once 'headers.php';
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $json = json_decode(file_get_contents("php://input"));
    $id_to_delete = (int)$json->{'id'};
    $pdo_connect->query("DELETE FROM ToDo WHERE id = '$id_to_delete'");
    $pdo_connect = null;
    echo json_encode(array("ok" => true));
}
