<?php

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Create new database and connect to it
    $database = new Database;
    $db = $database->connect();

    //Instantiate quote object
    $quote = new Quote($db);

    // Get filter parameters if provided
    $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    // Quote query with optional filters
    $result = $quote->read($author_id, $category_id);

    $num = $result->rowCount();

    if($num>0) {

        // Create Array
        $quotes_array = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            $quotes_array[] = $quote_item; //Push to array directly
        }
    
        echo json_encode($quotes_array); //Return array, not object
    } else {
        echo json_encode(array('message' => 'No Quotes Found')); //Return an empty array instead of an object
    }