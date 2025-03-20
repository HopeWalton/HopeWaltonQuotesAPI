<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

$category = new Category($db);

// Get ID
$category->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["message" => "No ID provided"]));

// Get Category
$category->read_single();

// Check if a category was found
if($category->id !== null){
    $cat_array = array(
        'id' => $category->id,
        'category' => $category->category
    );

    echo json_encode($cat_array);
} else {
    echo json_encode(['message' => 'category_id Not Found']);
}