<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

$author = new Author($db);

// Get ID
$author->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["message" => "No ID provided"]));

// Get author
$author->read_single();

// Check if an author was found
if ($author->id !== null) {
    // Create Array
    $author_array = array(
        'id' => $author->id,
        'author' => $author->author
    );

    // Return JSON response
    echo json_encode($author_array);
} else {
    echo json_encode(['message' => 'Author not found']);
}
