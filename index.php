<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Default API message
echo json_encode(["message" => "API is working"]);
