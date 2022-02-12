<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);


$user->_id = isset($_GET['userId'])? $_GET['userId'] : die();

$user->read_single();

$user_arr = array(
    "_id" => $user->_id,
    "name" => $user->name,
    "email" => $user->email,
    "phone" => $user->phone,
    "address" => $user->address,
);

print_r(json_encode($user_arr));