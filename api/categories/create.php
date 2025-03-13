<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

$category = new Category($db);

 // Get raw posted data
 $data = json_decode(file_get_contents("php://input"));

 $category->category = $data->category;

if($category->create()){
    echo json_encode(array('message'=>'New Category created!'));
} else {
    echo json_encode(array('message'=>'Category not created'));
}