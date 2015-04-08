<?php namespace App\Models ;

class Note extends Model {
    public $id ;
    public $title ;
    public $description ;
    public $alertdate ;
    public $enabled ;
    public $user_id ;
    
    public function __construct($properties = array()){
        $this->setProperties($properties);
    }
    
    public function setProperty($name, $value){
        if ($name == "enabled"){
            $this->enabled = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        } else
            $this->$name = $value ;
    }
}

?>