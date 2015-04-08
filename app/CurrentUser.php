<?php namespace App;

use App\Services\DBConnector ;
use App\Services\Session ;
use App\Repositories\UserRepository ;

class CurrentUser extends Models\User {
    private $db ;
    private $session ;
    private $container ;
    
    private static $instance ;
    
    
    public function __construct(DBConnector $db, Session $session){
        $this->db = $db ;
        $this->container = &$session->getContainer(__CLASS__);
        self::setProperties($this->container);
        
        self::$instance = $this ;
    }
    
    public function login($email, $password){
        $user = UserRepository::find(
            array(
                "email" => $email, 
                "password" => self::getHash($password)
            ), 1
        );
        if ($user){
            $this->container['id']          = $user->id ;
            $this->container['email']       = $user->email ;
            $this->container['password']    = $user->password ;
            $this->container['firstname']   = $user->firstname ;
            $this->container['lastname']    = $user->lastname ;
            $this->container['phone']       = $user->phone ;
            
            $this->setProperties($this->container);
            
            return true ;
        }
        return false ;
    }
    
    public function logout(){
        $this->container = array();
    }
    
    public function register(){
    
    }
    
    public function isLogged(){
        return isset($this->container['id']) ;
    }
    
    private function getHash($data){
        return md5($data);
    }
    
    /** @return CurrentUser instance */
    public static function getInstance(){
        return self::$instance ;
    }
}
?>