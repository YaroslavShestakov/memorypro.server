<?php

function init(){
    $input = file_get_contents("php://input",0, null, null);
    $request = json_decode($input);

    if (!$request){
        Response::display(Response::create(Response::UNKNOWN_REQUEST));
        exit();
    }

    $types = array(    
        "connect" => function($request){  return true ; },
        
        "login"   => function($request){
            if (isset($request->username) && isset($request->password)){
                if ($request->username == "test" && $request->password == "test")
                    return true ;
                return false ;
            }
        });
    
    
    $status = -1 ;
    if (!empty($request->type)){


        if (isset($types[$request->type])){
            $status = $types[$request->type]();
        }
        
        
    }
    
    
    Response::display(Response::create($status));
}


function toObject($stuff){
    if (is_array($stuff)){
        $stuff = (object) $stuff ;
        foreach($stuff as &$part){
            $part = toObject($part);
        }
    }
    return $stuff ;
}