<?php
require_once 'constants.php';

function connectDB() {
    $db_connect= null;
    try{
        $db_connect = new PDO('mysql:host=localhost;dbname=library_base;charset=utf8', DB_USER, DB_PASSWORD);
        $db_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$db_connect ->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    } catch (PDOException $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }
    return $db_connect;
}
