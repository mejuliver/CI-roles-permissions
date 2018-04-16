<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_controller extends CI_Controller {


	function __construct(){
	    
		// to check if authenticated
	if( isAuth() ){

	}

	// to check if user has the given role
	if( isAuth() && inRole() ){

	}

	// to check if user has the given permission
	if( isAuth() && canPerm() ){
		
	}

		parent::__construct();
	}


}


