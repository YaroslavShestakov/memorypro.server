<?php namespace App\Services ;

use \App\Response as Response ;

class APIService {
    
    protected $request ;
    /** @var \App\Response Description */
    protected $response ;
    protected $actions ;
    
    public function __construct($request){
        $this->request = $request ;
        $this->response = new Response;
        $this->actions = new APICommands;
    }
    
    public function process()
    {
        if (empty($this->request->action))
            return false ;
        
        $action = $this->request->action ;
        
        if ($this->actions->has($action)){
            $this->response->action = $action ;
            $this->actions->{$action}($this->request, $this->response);
        }
        
    }
    
    public function getResponse()
    {
        return $this->response ;
    }
}
