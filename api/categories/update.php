<?php

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID and category are provided
    if (!isset($data->id) || !isset($data->category)) {
        echo json_encode(['message' => 'ID and category are required']);
        exit();
    }

    // Set ID to update
    $category->id = $data->id;

    $category->category = $data->category;
    
    //Update category
    if($category->update()){
        echo json_encode(
            array('message' => 'category Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'category Not Updated')
        );
    }