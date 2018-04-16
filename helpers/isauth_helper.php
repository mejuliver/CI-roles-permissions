<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('isAuth')) {
    function isAuth(){
        
    	$CI = & get_instance();

    	$res = false;

     	// check if theres a role first
        if( $CI->session->userdata('authenticated') != null && $CI->session->userdata('authenticated') ){
    		return true;
        }

        return $res;
    }   
}
