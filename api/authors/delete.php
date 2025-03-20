<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Check if Author Exists Before Deleting
    $check_author_query = "SELECT id FROM authors WHERE id = :id";
    $stmt = $db->prepare($check_author_query);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "No Authors Found"]);
        exit();
    }

    // Set ID to delete
    $author->id = $data->id;

    if ($author->delete()) {
        echo json_encode(["id" => $author->id]);  // 👈 Return ID instead of a message
    } else {
        echo json_encode(["message" => "Author Not Deleted"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
