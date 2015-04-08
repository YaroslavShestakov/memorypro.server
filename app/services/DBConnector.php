<?php namespace App\Services ;

class DBConnector extends \MySQLi {

    /** @var DBConnector */
    private static $instance = null ;

    public function __construct($host, $user, $pass, $name){
        @parent::__construct($host, $user, $pass, $name);
        
        self::$instance = $this ;
    }
    
    
    /**
     * 
     * @return DBConnector Description
     */
    public static function getInstance(){
        if (self::$instance == null)
            exit("DBConnector class was not instantiated.");
        else
            return self::$instance;
    }
    
    /**
     * 
     * @return boolean Checks if connection is successful
     */
    public function isConnected(){
       
        $instance = static::$instance ;
        return (!$instance->connect_errno && $instance->ping());
    }
}

?>