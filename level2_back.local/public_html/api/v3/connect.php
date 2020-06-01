<?php

$pdo_connect = null;
try {
    $pdo_connect = new PDO('mysql:host=localhost;dbname=todo_base;charset=utf8', 'backend_user', '1605');
    $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //create database if it not exist
    $sql = "CREATE DATABASE IF NOT EXISTS todo_base";
    $pdo_connect->exec($sql);

    $pdo_connect->exec("use todo_base");

    $sql = "CREATE TABLE IF NOT EXISTS  Users(
    id Serial,
login VARCHAR (100),
pass VARCHAR (100),
PRIMARY KEY (id))
ENGINE InnoDB CHARACTER SET utf8;";
    $pdo_connect->exec($sql);


    $sql = "CREATE TABLE IF NOT EXISTS ToDo (
    id SERIAL,
user_id BIGINT UNSIGNED NOT NULL,
text TEXT,
checked BOOLEAN,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES Users (id)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE InnoDB CHARACTER SET utf8;";

    $pdo_connect->exec($sql);
} catch (PDOException $e) {
    echo json_encode(array("error" => $e->getMessage()));
}