<?php

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // Create new category object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(['message' => 'ID is required']);
        exit();
    }

    // Set ID to delete
    $category->id = $data->id;

    if($category->delete()){
        echo json_encode(
            array('message' => 'category Deleted')
        );
    } else {
        echo json_encode(
            array('message'=> 'category Not Deleted')
        );
    }