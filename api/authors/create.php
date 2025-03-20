<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->author)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    $author->author = $data->author;

    if ($author->create()) {
        echo json_encode([
            "id" => $db->lastInsertId(),
            "author" => $author->author
        ]);
    } else {
        echo json_encode(["message" => "Author Not Created"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
