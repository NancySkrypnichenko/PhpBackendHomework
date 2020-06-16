<?php
require_once 'constants.php';

class ConnectBD
{
    private static $instances = null;

    private function __construct()
    {
    }


    public static function connectDB()
    {
        if (self::$instances === null) {
            try {
                self::$instances = new PDO('mysql:host=localhost;dbname=library_base;charset=utf8', DB_USER, DB_PASSWORD);
                self::$instances->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //$db_connect ->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            } catch (PDOException $e) {
                echo json_encode(array("error" => $e->getMessage()));
            }
        }
        return self::$instances;
    }
}
