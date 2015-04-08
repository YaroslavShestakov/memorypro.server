<?php namespace App\Repositories ;
 
use App\Services\DBConnector as DBConnector ;
use App\Models\User as User ;

class UserRepository {
    private static $fields    = array("id", "email", "password", "firstname", "lastname", "phone");
    private static $fillable  =       array("email", "password", "firstname", "lastname", "phone");
    private static $mandatory =       array("email", "password", "firstname") ;
    
    
    public static function add(User $user){
        $db = DBConnector::getInstance();
        $existing = array();
        foreach(self::$fields as $field){
            if (isset($user->$field)){
                $existing[$field] = "'{$db->real_escape_string($user->$field)}'"; //ESCAPING STRINGS FROM SQL HOLES HERE
            }
        }
        
        if (count(array_intersect(array_keys($existing), self::$mandatory)) >= count(self::$mandatory)){  //ALL MANDATORY FIELDS ARE FILLED
            $keys   = implode(", ", array_keys  ($existing));
            $values = implode(", ", array_values($existing));
            
            
        
            $sql = "
                INSERT INTO users ({$keys}) VALUES ({$values}) ;
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
                    id, email, password, firstname, lastname, phone 
                FROM users
                WHERE {$conditions} ;
            " ;
            
            
            $result = $db->query($sql);
            
            if ($result && $result->num_rows == 1){
                $user = $result->fetch_object();
                
                return $user ;
            }
        }
        return null ;
    }
    
    public static function create(){
        $db = DBConnector::getInstance();
        $sql = "
            CREATE TABLE users (
                id          INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
                email       VARCHAR(50) NOT NULL,
                password    VARCHAR(32) NOT NULL,
                firstname   VARCHAR(30) NOT NULL,
                lastname    VARCHAR(30),
                phone       VARCHAR(20),

                UNIQUE(email)
            ) Engine = InnoDB;
        " ;
        return $db->query($sql);
    }
    
    public static function drop(){
        $db = DBConnector::getInstance();
        $sql = "DROP TABLE users" ;
        return $db->query($sql);
    }
    
    public static function getHash($data){
        return md5($data);
    }
} 
 
?>