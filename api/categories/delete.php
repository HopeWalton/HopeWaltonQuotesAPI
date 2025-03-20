<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->connect();
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    // Ensure ID is provided
    if (!isset($data->id)) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Check if Category Exists Before Deleting
    $check_category_query = "SELECT id FROM categories WHERE id = :id";
    $stmt = $db->prepare($check_category_query);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        echo json_encode(["message" => "No Categories Found"]);
        exit();
    }

    // Set ID to delete
    $category->id = $data->id;

    if ($category->delete()) {
        echo json_encode(["id" => $category->id]);  // 👈 Return ID instead of a message
    } else {
        echo json_encode(["message" => "Category Not Deleted"]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
