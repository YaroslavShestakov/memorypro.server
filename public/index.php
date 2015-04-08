<?php require("../config/config.php");

use \App\Services\APIService as API ;

$request = toObject($_REQUEST);

$api = new API($request);
$api->process();

$response = $api->getResponse();



//header('Content-Type: application/json');
header("HTTP/1.1 200 OK");

echo $response ;