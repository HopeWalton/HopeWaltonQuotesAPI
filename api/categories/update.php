<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    // Check for valid JSON input
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON input"]);
        exit();
    }

    // Ensure ID and category are provided
    if (!isset($data->id) || !isset($data->category)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Assign Data to Category Object
    $category->id = (int) $data->id;
    $category->category = $data->category;

    // Perform Update
    if ($category->update()) {
        echo json_encode([
            "id" => $category->id,
            "category" => $category->category
        ]);
        exit();
    } else {
        echo json_encode(["message" => "Category Not Updated"]);
        exit();
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}
