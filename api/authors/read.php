<?php

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Create new database and connect to it
    $database = new Database;
    $db = $database->connect();

    //Instantiate author object
    $author = new Author($db);

    // Author query
    $result = $author->read();

    // Get row count
    $num = $result->rowCount();

    // Check if there are any authors
    if($num > 0) {
        // Create Array
        $author_array = ['data' => []];

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $author_item = array(
                'id' => $id,
                'author' => $author
            );

            array_push($author_array['data'], $author_item);
        }

        // Turn to json
        echo json_encode($author_array);
    } else {
        echo json_encode(array('message'=> 'No authors found'));
    }