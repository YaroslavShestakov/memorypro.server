<?php namespace App\Repositories ;

use \App\Models\Note as Note ;
use \App\Services\DBConnector as DBConnector;

class NoteRepository extends Repository {
    protected static $table = "notes" ;
    protected static $container = "Note" ;
    
    protected static $fields    = array("id", "title", "description", "alertdate", "enabled", "user_id");
    protected static $fillable  = array("title", "description", "alertdate", "enabled", "user_id");
    protected static $mandatory = array("title", "description", "alertdate", "enabled", "user_id") ;
    
    public static function create(){
        $db = DBConnector::getInstance();
        $sql = "
            CREATE TABLE notes (
                id          INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
                title       VARCHAR(40) NOT NULL,
                description VARCHAR(300) NOT NULL,
                alertdate   TIMESTAMP NOT NULL,
                enabled     BOOLEAN NOT NULL,
                user_id     INTEGER NOT NULL,
                
                FOREIGN KEY(user_id) REFERENCES users(id)
            ) Engine = InnoDB;
        " ;
        return $db->query($sql);
    }
}

?>