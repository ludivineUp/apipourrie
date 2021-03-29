<?php

openlog("myLog", LOG_PID | LOG_PERROR, LOG_LOCAL0);

include_once $_SERVER['DOCUMENT_ROOT'] . '/models/Pony.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");

try {
    $json_str = file_get_contents('php://input');
    $pony = json_decode($json_str);
    addPony($pony);
    $access = date("Y/m/d H:i:s");
    syslog(LOG_INFO ,$access. " : add pony works : ".$pony->id);
    ///http_response_code(200);
    //return;
} catch (Exception $ex) {
    $access = date("Y/m/d H:i:s");
	syslog(LOG_ERR , $access. " : error add pony ".$ex->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
    return;
}   
closelog();