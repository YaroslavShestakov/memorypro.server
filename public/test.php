<?php

require '../config/config.php' ;

$db = \App\Services\DBConnector::getInstance();


echo "Connection: " . var_export($db->connected(), true);


$user = \App\CurrentUser::getInstance();
$data = array(
    "logged" => $user->isLogged(),
    "email"  => $user->email,
    "firstname" => $user->firstname,
    "lastname" => $user->lastname,
    "phone"  => $user->phone,
);

echo "<br/><br/>"
. "User data: " ;
var_dump($data);
