<?php

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // Create new author object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(['message' => 'ID is required']);
        exit();
    }

    // Set ID to delete
    $author->id = $data->id;

    if($author->delete()){
        echo json_encode(
            array('message' => 'Author Deleted')
        );
    } else {
        echo json_encode(
            array('message'=> 'Author Not Deleted')
        );
    }