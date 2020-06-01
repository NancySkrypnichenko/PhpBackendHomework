<?php
session_start();
require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = json_decode(file_get_contents("php://input"));

    $login = $json->login;
    $password = md5($json->pass);

    $users = $pdo_connect->query("SELECT * FROM `Users` WHERE `login` = '$login' AND `pass` = '$password'");

    if ($users->rowCount() == 0) {
        report_about_authorization_error("User with such data not found");
    }
    $user = $users->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user'] = ["id" => $user['id']];
    $pdo_connect = null;
    echo json_encode(array("ok" => true));
}
