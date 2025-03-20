<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    // Check for valid JSON input
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON input"]);
        exit();
    }

    // Ensure ID and Author are provided
    if (!isset($data->id) || !isset($data->author)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Assign Data to Author Object
    $author->id = (int) $data->id;
    $author->author = $data->author;

    // Perform Update
    if ($author->update()) {
        echo json_encode([
            "id" => $author->id,
            "author" => $author->author
        ]);
        exit();
    } else {
        echo json_encode(["message" => "Author Not Updated"]);
        exit();
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}
