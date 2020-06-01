<?php
session_start();

require_once 'headers.php';
require_once 'connect.php';
require_once 'service.php';

$login = (int)$_SESSION['user']['id'];
$json = array('items' => []);

// after taken all information from base about task of current user should make json as front answer
foreach ($pdo_connect->query('SELECT * FROM ToDo Where user_id = ' . $login . ';', PDO::FETCH_ASSOC) as $todos) {
    $todos['checked'] = $todos['checked'] == 1;
    array_push($json['items'], $todos);
}
$pdo_connect = null;
echo json_encode($json);
