<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('isAuth')) {

    function isAuth(){

    	$CI = & get_instance();


     	// check if theres a role first

        if( $CI->session->userdata('auth') != null || $CI->session->userdata('auth') !== false ){

        	// set the auth to false
        	$CI->session->set_userdata('auth',false);
    		return true;

        }

        // set the auth to false
        $CI->session->set_userdata('auth',true);
        return false;

    }   

}

