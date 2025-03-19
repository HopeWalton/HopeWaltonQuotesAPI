<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Ensure required parameters are present
if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Ensure values are not empty
if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
    echo json_encode(['message' => 'Fields cannot be empty']);
    exit();
}

// Ensure author_id and category_id are numbers
if (!is_numeric($data->author_id) || !is_numeric($data->category_id)) {
    echo json_encode(['message' => 'author_id and category_id must be integers']);
    exit();
}

// Check if author exists
$check_author_query = "SELECT id FROM authors WHERE id = :author_id";
$stmt = $db->prepare($check_author_query);
$stmt->bindParam(':author_id', $data->author_id, PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    die(json_encode(["message" => "author_id Not Found"]));
}

// Check if category exists
$check_category_query = "SELECT id FROM categories WHERE id = :category_id";
$stmt = $db->prepare($check_category_query);
$stmt->bindParam(':category_id', $data->category_id, PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    die(json_encode(["message" => "category_id Not Found"]));
}

// Assign data to Quote object
$quote->quote = $data->quote;
$quote->author_id = (int) $data->author_id;
$quote->category_id = (int) $data->category_id;

// Create the quote and get new ID
$new_quote_id = $quote->create();

if ($new_quote_id) {
    echo json_encode([
        'id' => $new_quote_id,
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    ]);
} else {
    echo json_encode(['message' => 'Quote Not Created']);
}

exit();
?>
