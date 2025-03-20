<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    // Debugging log
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON input"]);
        exit();
    }

    if (!isset($data->author)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    $author->author = $data->author;

    // Create Author & Return ID
    $newId = $author->create(); // This should return the ID from the query
    if ($newId) {
        echo json_encode([
            "id" => (int) $newId,  // Force ID to be an integer
            "author" => $author->author
        ]);
        exit();
    } else {
        echo json_encode(["message" => "Author Not Created"]);
        exit();
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}
