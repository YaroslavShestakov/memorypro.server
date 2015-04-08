<?php namespace App\Models ;

abstract class Model {
        
    public function setProperties($properties){
        $properties = (array) $properties ;
        foreach($properties as $name => $value){
            $this->setProperty($name, $value);
        }
        return $this ;
    }
    
    public abstract function setProperty($name, $value);
}

?>