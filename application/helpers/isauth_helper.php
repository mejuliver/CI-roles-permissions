<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('isAuth')) {

    function isAuth(){

    	$CI = & get_instance();

     	// check if theres a role first

        if( $CI->session->userdata('auth')){

        	// set the auth to false
        	$CI->session->set_userdata('auth',true);
    		return true;

        }else{
            // set the auth to false
            $CI->session->set_userdata('auth',false);
            $CI->session->unset_userdata('user_info');
            return false;
        } 
        
    }   

}

