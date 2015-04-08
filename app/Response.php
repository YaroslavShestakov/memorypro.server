<?php namespace App ;

class Response {
    const 
        UNKNOWN_REQUEST = -1,
        FAIL            = 0,
        SUCCESS         = 1 ;
    
    public $action ;
    public $status ;
    public $message ;
    public $data ;
    public $transaction ;

    public static function create($status = -1){
        $response = new STDClass();       
        $response->status = $status ;
        
        return $response ;
    }
    
    public static function display($response){
        var_dump($response);
        echo json_encode($response);
    }
    
    public function __toString() {
        $json = json_encode(get_object_vars($this));
        return $json ;
    }
}
