<?php

require('db.credentials.php');
require('functions.php');

spl_autoload_register(function($class){
    if (!class_exists($class)){
        $parts = explode('\\', $class);
        require "../" . implode('/', $parts) . ".php" ;
    }
});


use \App\Services\Session as Session ;
use \App\Services\DBConnector as DBConnector;
use \App\CurrentUser as CurrentUser ;


$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null ;

$session        = new Session($sid);
$db             = new DBConnector(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$current_user   = new CurrentUser($db, $session);
