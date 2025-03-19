<?php

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Author object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID and Author are provided
    if (!isset($data->id) || !isset($data->author)) {
        echo json_encode(['message' => 'ID and Author are required']);
        exit();
    }

    // Set ID to update
    $author->id = $data->id;
    $author->author = $data->author;
    
    //Update Author
    if($author->update()){
        echo json_encode(
            array('message' => 'Author Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Author Not Updated')
        );
    }