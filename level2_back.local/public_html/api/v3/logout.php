<?php
session_start();
require_once 'headers.php';

unset($_SESSION ['user']);
echo json_encode(array("ok" => true));
