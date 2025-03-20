<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Set Headers
header('Content-Type: application/json');

try {
    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check for missing fields
    if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Validate if author_id exists
    $check_author_query = "SELECT id FROM authors WHERE id = :author_id";
    $stmt = $db->prepare($check_author_query);
    $stmt->bindParam(':author_id', $data->author_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "author_id Not Found"]);
        exit();
    }

    // Validate if category_id exists
    $check_category_query = "SELECT id FROM categories WHERE id = :category_id";
    $stmt = $db->prepare($check_category_query);
    $stmt->bindParam(':category_id', $data->category_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "category_id Not Found"]);
        exit();
    }

    // Assign data to Quote object
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Attempt to create the quote
    if ($quote->create()) {
        echo json_encode([
            "id" => $db->lastInsertId(),
            "quote" => $quote->quote,
            "author_id" => $quote->author_id,
            "category_id" => $quote->category_id
        ]);
    } else {
        echo json_encode(["message" => "Quote Not Created"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
