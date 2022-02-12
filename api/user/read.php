<?php
// Headers

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

if(isset($_GET['limit']) && isset($_GET['limit'])){
    $user->limit = $_GET['limit'];
    $user->startIndex= ($_GET['currentPage'] -1) * $_GET['limit'];
}

if(isset($_GET['byName']) && $_GET['byEmail'] && $_GET['searchText']){
    $user->nameBoolean = json_decode($_GET['byName']);
    $user->emailBoolean = json_decode($_GET['byEmail']);
    $user->searchText =$_GET['searchText'];
}
    

$result = $user->read();


$users_arr = array();
$users_arr['users'] = array();
$users_arr['count'] = $result['count'];

while($row = $result['users']->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $user_item = array(
        '_id' => $_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
    );


    array_push($users_arr['users'], $user_item);

}

echo json_encode($users_arr);