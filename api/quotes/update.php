<?php

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    $quote = new quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure all fields are provided
    if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit();
    }
    
    if (!is_numeric($data->id) || !is_numeric($data->author_id) || !is_numeric($data->category_id)) {
        echo json_encode(['message' => 'Invalid ID']);
        exit();
    }
    
    // Check if the quote exists before updating
    $check_query = "SELECT id FROM quotes WHERE id = :id";
    $stmt = $db->prepare($check_query);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        die(json_encode(["message" => "No Quotes Found"]));
    }
    
    // Update quote
    if ($quote->update()) {
        echo json_encode([
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Updated']);
    }
    