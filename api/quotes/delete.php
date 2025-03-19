<?php

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // Create new quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(['message' => 'ID is required']);
        exit();
    }

    // Set ID to delete
    $quote->id = $data->id;

    if($quote->delete()){
        echo json_encode(
            array('message' => $quote->id . ' Deleted')
        );
    } else {
        echo json_encode(
            array('message'=> 'No Quotes Found')
        );
    }