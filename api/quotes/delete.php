<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $quote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Check if Quote Exists Before Deleting
    $check_quote_query = "SELECT id FROM quotes WHERE id = :id";
    $stmt = $db->prepare($check_quote_query);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "No Quotes Found"]);
        exit();
    }

    // Set ID to delete
    $quote->id = $data->id;

    if ($quote->delete()) {
        echo json_encode(["id" => $quote->id]);  // 👈 Return ID instead of a message
    } else {
        echo json_encode(["message" => "Quote Not Deleted"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
