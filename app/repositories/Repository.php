<?php   namespace App\Repositories ;

use \App\Services\DBConnector as DBConnector;

abstract class Repository {
    /*public static $table ;*/
    /*public static $container ;*/
    
    public static function add($object){
        $db = DBConnector::getInstance();
        $existing = array();
        foreach(static::$fields as $field){
            if (isset($object->$field)){
                $existing[$field] = "'{$db->real_escape_string($object->$field)}'"; //ESCAPING STRINGS FROM SQL HOLES HERE
            }
        }
       
        if (count(array_intersect(array_keys($existing), static::$mandatory)) >= count(static::$mandatory)){  //ALL MANDATORY FIELDS ARE FILLED
            $keys   = implode(", ", array_keys  ($existing));
            $values = implode(", ", array_values($existing));      
        
            $sql = "
                INSERT INTO ".static::$table." ({$keys}) VALUES ({$values}) ;
            " ;
            
            $result = $db->query($sql);
            return $result ;
        
        }
        return false ;
    }
    
    public static function find($terms, $number = null){
        $db = DBConnector::getInstance();
        if ($db->ping()){
            $conditions = array();
            foreach($terms as $name => $value){
                $name  = $db->real_escape_string($name);
                $value = $db->real_escape_string($value);
                
                $conditions[] = "{$name} = '{$value}'" ;
            }
            
            $conditions = implode(" AND ", $conditions);
            
            $sql = "
                SELECT 
                    ".implode(", ", static::$fields)." 
                FROM " . static::$table . "
                WHERE {$conditions} ;
            " ;
            
            
            $result = $db->query($sql);
            
            if ($result && $result->num_rows >= 1){
                $objects = array() ;
                
                while ($data = $result->fetch_assoc()){
                    $objects[] = new static::$container($data);
                }
                
                return $objects ;
            }
        }
        return null ;
    }
    
    public static function modify($object){
        $db = DBConnector::getInstance();
        if ($db->ping()){
            $sets = array();
            foreach(static::$fillable as $field){
                if (isset($object->$field)){
                    $sets[$field] = $db->real_escape_string($object->$field) ;
                }
            }
            
            if (count($sets) >= 1){
                $properties = array() ;
                foreach($sets as $key => $value){
                    $properties[] = "{$key} = '{$value}'" ;
                }
                
                $properties = implode(", ", $properties);
            
                $sql = "
                    UPDATE " . static::$table . " 
                    SET {$properties}
                    WHERE id = '{$object->id}' ;
                " ;               
                
                $result = $db->query($sql);
                
                if ($result && $db->affected_rows >= 1){
                    return true ;
                }
            }
        }
        return false ;
    }
    
    public static function remove($object){
        $db = DBConnector::getInstance();
        if ($db->ping()){
            $id = $db->real_escape_string($object->id);
            $sql = "
                DELETE FROM ". static::$table . "
                WHERE id = '{$id}' ;
            " ;
            $result = $db->query($sql);
            if ($result && $db->affected_rows >= 1){
                return true ;
            }
        }
        return false ;
    }
    
    public static function drop(){
        $db = DBConnector::getInstance();
        $sql = "DROP TABLE " . static::$table . " " ;
        return $db->query($sql);
    }
    
            
    public static function getHash($data){
        return md5($data);
    }
    
    public static function create(){}
}

?>