<?php

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Create new database and connect to it
    $database = new Database;
    $db = $database->connect();

    //Instantiate quote object
    $quote = new Quote($db);

    $quote->id = isset($_GET['id']) ? (int) $_GET['id'] : die(json_encode(["message" => "No ID provided"]));

    // Get the quote
    $quote->read_single();

    if($quote->id !== null) {
        $quote_array = [
            'id' => $quote->id,
            'quote' => $quote->quote,
            'category' => $quote->category,
            'author' => $quote->author
        ];

        echo json_encode($quote_array);
    } else {
        echo json_encode(['message' => 'No Quotes found']);
    }