<?php namespace App\Services ;

use App\Response as Response ;
use App\CurrentUser as CurrentUser ;
use App\Services\Session as Session ;
use App\Repositories\NoteRepository as NoteRepository;
use App\Repositories\UserRepository as UserRepository;
use App\Models\User as User ;
use App\Models\Note as Note ;

class APICommands {
    
    public function register($request, Response $response)
    {
        if (isset(
                $request->data->email,
                $request->data->password,
                $request->data->firstname
        )){
            
            $result = UserRepository::add(
                new User(
                    array(
                        "email"     => $request->data->email,
                        "password"  => $request->data->password,
                        "firstname" => $request->data->firstname,
                        "lastname"  => isset($request->data->lastname) ? $request->data->lastname : "",
                        "phone"     => isset($request->data->phone)    ? $request->data->phone    : ""
                    )
                )
            );
            
            if ($result){
                $response->status = Response::SUCCESS ;
                $response->message = "Registration successful" ;
            } else {
                $response->status = Response::FAIL ;
                $response->message = "Could not register" ;
            }     
       } else {
           $response->status = Response::FAIL ;
           $response->message = "Email, Password, Firstname are mandatory";
       }
    }
    
    public function login($request, Response $response)
    {
        $session = Session::getInstance();
        $user = CurrentUser::getInstance();
        if ($user->isLogged()){
            $response->status  = true ;
            $response->message = "Already logged in" ;
            $response->data    = array("user" => $user->getData(), "sid" => $session->id());
        } else {
            if (isset($request->data->email, $request->data->password)){
                if ($user->login($request->data->email, $request->data->password)){
                    $response->status  = true ;
                    $response->message = "Authentication successful" ;
                    $response->data    = array("user" => $user->getData(), "sid" => $session->id());
                } else {
                    $response->status = false ;
                    $response->message = "Authentication failed" ;
                }
            } else {
                $response->status = false ;
                $response->message = "Email and password must be supplied" ;
            }
        }
    }
    
    public function logout($requst, Response $response)
    {
        $user = CurrentUser::getInstance();
        $user->logout();
    }
    
    public function db_status($request, Response $response)
    {
        $db = \App\Services\DBConnector::getInstance();
        
        $connected = $db->isConnected();  
        
        if ($connected){
            $response->status = Response::SUCCESS ;
            $response->message = "Database connected and running" ;
        } else {
            $response->status = Response::FAIL ;
            $response->message = "Database is down" ;
        }
    }
    
    public function add_note($request, Response $response)
    {
        $user = CurrentUser::getInstance();
        if ($user->isLogged()){
                if (isset($request->data)){
                    $request->data->user_id = $user->id ;  //Relate note to this current user.
                
                    $note = new Note($request->data);
                    if (NoteRepository::add($note)){
                        $response->status  = Response::SUCCESS ;
                        $response->message = "Note added successfully." ;
                    } else {
                        $response->status  = Response::FAIL ;
                        $response->comment = "Could not add note to database" ;
                    }
            } else
                $response->status  = Response::FAIL ;
                $response->message = "Insufficient data" ;
        } else {
            $response->status  = Response::FAIL ;
            $response->message = "User must be logged in" ;
        }
    }
   
    public function edit_note($request, Response $response)
    {
        $user = CurrentUser::getInstance();
        if ($user->isLogged()){
            if (isset($request->data->id)){
                $note = new Note($request->data);
                $note->user_id = $user->id ;
                
                if (NoteRepository::modify($note)){
                    $response->status = Response::SUCCESS ;
                    $response->message = "Note successfully edited" ;
                } else {
                    $response->status = Response::FAIL ;
                    $response->message = "Could not modify object in the database" ;
                }
            } else {
                $response->status = Response::FAIL ; 
                $response->message = "No data supplied" ;
            }
        } else {
            $response->status = Response::FAIL;
            $response->message = "User not logged in";
        }
    }
            
            
    public function delete_note($request, Response $response)
    {
        $user = CurrentUser::getInstance();
        if ($user->isLogged()) {
            if (isset($request->data->id)) {
                $note = new Note($request->data);
                $note->user_id = $user->id;

                if (NoteRepository::remove($note)) {
                    $response->status = Response::SUCCESS;
                    $response->comment = "Note successfully deleted";
                } else {
                    $response->status = Response::FAIL;
                    $response->comment = "Could not delete note";
                }
            } else {
                $response->status = Response::FAIL;
                $response->comment = "No data supplied";
            }
        } else {
            $response->status = Response::FAIL;
            $response->comment = "User not logged in";
        }
    }
    
    public function get_notes($request, Response $response)
    {
        $user = CurrentUser::getInstance();
        if ($user->isLogged()){
            $notes = NoteRepository::find(
                array("user_id" => $user->id)
            );
            
            $response->status = Response::SUCCESS ;
            $response->message = "Notes succefully fetched" ;
            $response->data = $notes ;
        } else {
            $response->status = Response::FAIL ;
            $response->message = "User must be logged in" ;
        }
    }

    public static function has($action)
    {
        return method_exists(new self, $action);
    }
}
