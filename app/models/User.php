<?php namespace App\Models ;

class User extends Model {
    public $id ;
    public $email ;
    public $password ;
    public $firstname ;
    public $lastname ;
    public $phone ;
    
    
    public function __construct($properties = array()){
        $this->setProperties($properties);
    }

    
    public function setProperty($name, $value){
        if ($name == "password")
            $value = $this->getHash($value);
            
        $this->$name = $value ;
        return $this ;
    }
    
    private function getHash($data){
        return md5($data);
    }
    
    public function getData()
    {
        $data = array(
            "id"        => $this->id,
            "email"     => $this->email,
            "firstname" => (string) $this->firstname,
            "lastname"  => (string) $this->lastname,
            "phone"     => (string) $this->phone
        );
        
        return $data;
    }
}

?>