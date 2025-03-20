<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->category)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    $category->category = $data->category;

    if ($category->create()) {
        echo json_encode([
            "id" => $db->lastInsertId(),
            "category" => $category->category
        ]);
    } else {
        echo json_encode(["message" => "Category Not Created"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
