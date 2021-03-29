<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/models/Pony.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $ponies = getPony(substr($_SERVER['REQUEST_URI'],21));
    http_response_code(200);
    
    echo json_encode($ponies);
    return;
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
    return;
}    
