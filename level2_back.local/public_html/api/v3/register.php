<?php
session_start();

require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = json_decode(file_get_contents("php://input"));

    //validation of input parameter
    $login = filter_var(trim($json->login)) or report_about_authorization_error("invalid username");
    $password = filter_var(trim($json->pass)) or report_about_authorization_error("invalid password");

    //if user name already exist - should use another username

    $users = $pdo_connect->query("SELECT * FROM `Users` WHERE `login` = '$login'");
    if ($users->rowCount() > 0) {
        report_about_authorization_error("such login already exists");
    }
    $password = md5($password);

    //registration of new user
    $pdo_connect->query("INSERT INTO Users (login,pass) VALUES ('$login', '$password');");
    $pdo_connect = null;
    echo json_encode(array("ok" => true));
}