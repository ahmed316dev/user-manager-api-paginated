<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(!$data) return;
$user->name = $data->name;
$user->email = $data->email;
$user->phone = $data->phone;
$user->address = $data->address;

if($user->create()){
    echo json_encode(
        array('message' => "User Created")
    );
}else{
    echo json_encode(
        array('message' => "User Created")
    );
}