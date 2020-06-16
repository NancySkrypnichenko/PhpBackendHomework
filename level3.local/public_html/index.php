<?php
// FRONT CONTROLLER
require_once 'config/constants.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once(ROOT . '/components/Router.php');

$router = new Router();
$router->run();