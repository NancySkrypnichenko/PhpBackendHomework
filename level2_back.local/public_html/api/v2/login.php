<?php
session_start();
require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //get information from input json
    $json = json_decode(file_get_contents("php://input"));
    $login = $json->login;
    $password = md5($json->pass);

    $connect = connect();
    $check_user = mysqli_query($connect, "SELECT * FROM `Users` WHERE `login` = '$login' AND `pass` = '$password'") or report_about_error_database($connect);

    // if no one result - user is not exist
    if (mysqli_num_rows($check_user) == 0) {
        report_about_authorization_error("User with such data not found");
    }
    mysqli_close($connect);

    //if pear of login and password exist in base - should put it information in session
    $user = mysqli_fetch_assoc($check_user);
    $_SESSION['user'] = ["id" => $user['id']];

    echo json_encode(array("ok" => true));
}