<?php
session_start();
require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = json_decode(file_get_contents("php://input"));

    $connect = connect();

    //validation of input parameter
    $login = filter_var(trim($json->login)) or report_about_authorization_error("invalid username");
    $password = filter_var(trim($json->pass)) or report_about_authorization_error("invalid password");

    //if user name already exist - should use another username
    check_input_login_uniqueness($login, $connect);
    $password = md5($password);

    //registration of new user
    $register_user = mysqli_query($connect, "INSERT INTO Users (login,pass) VALUES ('$login', '$password');") or report_about_error_database($connect);
    mysqli_close($connect);
    echo json_encode(array("ok" => true));

}

/**
 * @param $string_login :login from input after validation
 * @param $link_connect :connect with database
 */
function check_input_login_uniqueness($string_login, $link_connect)
{
    $check_user = mysqli_query($link_connect, "SELECT * FROM `Users` WHERE `login` = '$string_login'");
    if (mysqli_num_rows($check_user) > 0) {
        report_about_authorization_error("such login already exists");
    }
}