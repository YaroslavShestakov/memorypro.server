<?php require "../config/config.php" ;

use \App\Repositories\UserRepository as UserRepository ;
use \App\Repositories\NoteRepository as NoteRepository ;

UserRepository::drop();
NoteRepository::drop();