<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$author = new Author($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Ensure required parameter is present
if (!isset($data->author) || empty($data->author)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Assign data to Author object
$author->author = $data->author;

// Create Author and get new ID
$new_author_id = $author->create();

if ($new_author_id) {
    echo json_encode([
        'id' => $new_author_id,
        'author' => $author->author
    ]);
} else {
    echo json_encode(['message' => 'Author Not Created']);
}

exit();
?>
