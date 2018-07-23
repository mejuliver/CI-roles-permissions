<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('inRole')) {

    function inRole($role_name=false){

        $CI = & get_instance();
        $res = false;

        // check if there are no roles and permissions
        if( $CI->session->userdata('roles_perms') == null || $CI->session->userdata('roles_perms') == '' ){
            return false;
        }


        if( $role_name ){
            if (is_array($role_name) or ($role_name instanceof Traversable)){
                $tmp_arr = [];
                foreach( $role_name as $r){

                    

                    if( in_array(strtolower($r), $CI->session->userdata('roles_perms')['roles'] )){
                        array_push($tmp_arr, 1);
                    }else{
                        array_push($tmp_arr, 0);
                    }
                }
                if( in_array(0, $tmp_arr )){
                    return false;
                }else{
                    return true;
                }

            }else{
                // check if theres a role first

                if( $CI->session->userdata('roles_perms') != null && $CI->session->userdata('roles_perms') != '' && $role_name ){

                    if( in_array(strtolower($role_name), $CI->session->userdata('roles_perms')['roles'] )){
                        return true;
                    }


                }
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
        if( $CI->session->userdata('roles_perms') == null || $CI->session->userdata('roles_perms') == '' ){
            return false;
        }

        if( $perm_name ){
           if (is_array($perm_name) or ($perm_name instanceof Traversable)){
                $tmp_arr = [];
                foreach( $perm_name as $p){

                    

                    if( in_array(strtolower($p), $CI->session->userdata('roles_perms')['permissions'] )){
                        array_push($tmp_arr, 1);
                    }else{
                        array_push($tmp_arr, 0);
                    }
                }
                if( in_array(0, $tmp_arr )){
                    return false;
                }else{
                    return true;
                }
            } else {
                // check if theres a role first
                if( $CI->session->userdata('roles_perms') != null && $CI->session->userdata('roles_perms') != '' && $perm_name ){

                    if( in_array(strtolower($perm_name), $CI->session->userdata('roles_perms')['permissions'] )){

                        return true;

                    }

                }

            }
        }

        


        return $res;

    }   

}
if( ! function_exists('getAllRoles') ){
    function getAllRoles(){

        $CI = & get_instance();

        $roles = $CI->db->get( 'roles' )->result();

        foreach( $roles as $r ){

            $perms_roles = $CI->db->get_where('perms_roles', [ 'roles_id'  => $r->id ])->result();
            $permissions = [];
            foreach ($perms_roles as $pr ) {
                $permissions[] = $CI->db->get_where('permissions', [ 'id' => $pr->perms_id ])->row();
            }
            $r->permissions = $permissions;

        }

        return $roles;
    }
}

if ( ! function_exists('getUsersPerRolesName') ){
    function getUsersPerRolesName($name){
        $CI =& get_instance();
        // get the id of the requested role first
        $role_name = strtolower($name);
        $role_id = $CI->db->get_where('roles', [ 'label' => $role_name ])->row();

        if( $role_id != NULL ){

            $users_roles = $CI->db->get_where('roles_users', [ 'roles_id' => $role_id->id ])->result();
            $users = [];
            foreach( $users_roles as $ur ){
                $users[] = $CI->db->get_where('profile', [ 'users_id' => $ur->users_id ])->row();
            }

            return $users;

        }else{
            return false;
        }

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


        $cndb = $CI->load->database('default',true);


        // get all the user roles

        $roles = $cndb->get_where( 'roles_users',[ 'users_id' => $id ] )->result();



        $new_roles = [];

        $new_perms = [];



        // build and set user roles

        foreach( $roles as $r){



            array_push($new_roles,strtolower($cndb->get_where('roles',['id' => $r->roles_id ])->row()->label ) );



            // get all the user role perms

            $perms = $cndb->get_where( 'perms_roles',[ 'roles_id' => $r->roles_id ] )->result();



            // build and set user roles

            foreach( $perms as $p ){



                array_push($new_perms,strtolower( $cndb->get_where('permissions',['id' => $p->perms_id ])->row()->label ) );

            }

        }



        

        $roles_perms = [ 'roles' => $new_roles, 'permissions' => $new_perms ];



        // set the role and permissions as session

        $CI->session->set_userdata('roles_perms',$roles_perms);
        

        return true;    

    }

}

