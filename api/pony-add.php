<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/models/Pony.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");

try {
    $json_str = file_get_contents('php://input');
    $pony = json_decode($json_str);
    addPony($pony);
    ///http_response_code(200);
    //return;
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
    return;
}   