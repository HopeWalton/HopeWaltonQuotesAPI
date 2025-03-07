<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB and Connect
$database = new Database();
$db = $database->connect();

$author = new Author($db);

 // Get raw posted data
 $data = json_decode(file_get_contents("php://input"));

 $author->author = $data->author;

if($author->create()){
    echo json_encode(array('message'=>'New author created!'));
} else {
    echo json_encode(array('message'=>'Author not created'));
}