<?php namespace App\Services ;

class Session {
    
    protected static $instance = null ;
    private $id ;
    
    public function __construct($id = null){
        $path = realpath(dirname($_SERVER['DOCUMENT_ROOT'])) . "/storage/sessions" ;
        session_save_path($path);
        if (!is_null($id)){
            $this->id = $id ;
            $this->load($this->id);
            session_start();
        } else {
            session_start();
            session_destroy();            //Should use when live
            session_start();
            session_regenerate_id(true);
            $this->id = session_id();
        }
        static::$instance = $this;
    }
    
    public function &getContainer($name){
        if (!isset($_SESSION[$name]) || !is_array($_SESSION[$name])){
            $_SESSION[$name] = array();
        }
        return $_SESSION[$name];
    }
    
    public function load($id){
        session_id($id);
    }
    
    public function id(){
        return $this->id ;
    }
    
    /**
     * @return Session Description
     */
    public static function getInstance()
    {
        return static::$instance ;
    }
}

?>