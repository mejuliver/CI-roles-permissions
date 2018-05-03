<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('inRole')) {

    function inRole($role_name=false){

        

        $CI = & get_instance();



        $res = false;

        // check if there are no roles and permissions
        if( $CI->session->userdata('jroles_perms') == null || $CI->session->userdata('jroles_perms') == '' ){

            return false;

        }



        // check if theres a role first

        if( $CI->session->userdata('jroles_perms') != null && $CI->session->userdata('jroles_perms') != '' && $role_name ){



            if( in_array(strtolower($role_name), $CI->session->userdata('jroles_perms')['roles'] )){



                return true;

            }

            

        }



        return $res;

    }   

}



if ( ! function_exists('canPerm')) {

    function canPerm($perm_name=false){

        

        $CI = & get_instance();



        $res = false;


        // check if there are no roles and permissions
        if( $CI->session->userdata('jroles_perms') == null || $CI->session->userdata('jroles_perms') == '' ){

            return false;

        }


        // check if theres a role first

        if( $CI->session->userdata('jroles_perms') != null && $CI->session->userdata('jroles_perms') != '' && $perm_name ){

            if( in_array(strtolower($perm_name), $CI->session->userdata('jroles_perms')['permissions'] )){



                return true;

            }



        }



        return $res;

    }   

}



if ( ! function_exists('setRolesPerms')) {

    function setRolesPerms($id=false){

        $CI =& get_instance();

        $CI->load->library('session');

        // initialize defaults
        if( !$id ){
            $id = ( $CI->session->userdata('auth') != null && $CI->session->userdata('auth') == true ) ? $CI->session->userdata('user_info')['user_id'] : false; 

        }
        
        if( !$id ){

            return false;

        }


        $CI->load->database('chat',true);


        // get all the user roles

        $roles = $CI->db->get_where( 'role_users',[ 'user_id' => $id ] )->result();



        $new_roles = [];

        $new_perms = [];



        // build and set user roles

        foreach( $roles as $r){



            array_push($new_roles,strtolower($CI->db->get_where('roles',['id' => $r->role_id ])->row()->label ) );



            // get all the user role perms

            $perms = $CI->db->get_where( 'role_permissions',[ 'role_id' => $r->role_id ] )->result();



            // build and set user roles

            foreach( $perms as $p ){



                array_push($new_perms,strtolower( $CI->db->get_where('permissions',['id' => $p->perm_id ])->row()->label ) );

            }

        }



        

        $roles_perms = [ 'roles' => $new_roles, 'permissions' => $new_perms ];



        // set the role and permissions as session

        $CI->session->set_userdata('jroles_perms',$roles_perms);

        

        return true;    



    }

}

