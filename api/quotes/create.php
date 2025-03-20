<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $quote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));

    // Debugging log
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON input"]);
        exit();
    }

    // Ensure all required fields exist
    if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Validate Author ID
    $check_author_query = "SELECT id FROM authors WHERE id = :author_id";
    $stmt = $db->prepare($check_author_query);
    $stmt->bindParam(':author_id', $data->author_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "author_id Not Found"]);
        exit();
    }

    // Validate Category ID
    $check_category_query = "SELECT id FROM categories WHERE id = :category_id";
    $stmt = $db->prepare($check_category_query);
    $stmt->bindParam(':category_id', $data->category_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "category_id Not Found"]);
        exit();
    }

    // Assign values
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Create the quote
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
