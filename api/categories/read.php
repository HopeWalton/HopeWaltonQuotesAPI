<?php

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    // Category query
    $result = $category->read();

    // Get row count
    $num = $result->rowCount();

    if ($num > 0){
        // Create array
        $cat_array = ['data' => []];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $cat_item = array(
                'id' => $id,
                'category' => $category
            );

            array_push($cat_array['data'], $cat_item);
        }

        echo json_encode($cat_array);
    } else {
        echo json_encode(array('message'=> 'category_id Not Found'));
    }
   