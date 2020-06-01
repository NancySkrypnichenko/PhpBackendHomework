<?php
function connect()
{
    //create connect with mysql
    @$connect = mysqli_connect('localhost', 'backend_user', '1605');
    if (!$connect) {
        http_response_code(500);
        echo json_encode(array("error" => mysqli_connect_error()));
        exit;
    }
    create_database($connect);

    //add database to connect for next work
    mysqli_select_db($connect, "todo_base");
    create_todo_tables($connect);
    create_users_table($connect);

    return @$connect;
}

function report_about_error_database($connect)
{
    http_response_code(500);
    echo json_encode(array("error" => mysqli_error($connect)));
    exit;
}

function create_database($connect)
{
    $sql = "CREATE DATABASE IF NOT EXISTS todo_base";
    if (!mysqli_query($connect, $sql)) {
        report_about_error_database($connect);
    }
}

function create_todo_tables($connect)
{
    $sql = "CREATE TABLE IF NOT EXISTS ToDo (
    id SERIAL,
user_id BIGINT UNSIGNED NOT NULL,
text TEXT,
checked BOOLEAN,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES Users (id)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE InnoDB CHARACTER SET utf8;";
    if (!mysqli_query($connect, $sql)) {
        report_about_error_database($connect);
    }

}

function create_users_table($connect)
{
    $sql = "CREATE TABLE IF NOT EXISTS  Users(
    id Serial,
login VARCHAR (100),
pass VARCHAR (100),
PRIMARY KEY (id))
ENGINE InnoDB CHARACTER SET utf8;";
    if (!mysqli_query($connect, $sql)) {
        report_about_error_database($connect);
    }
}