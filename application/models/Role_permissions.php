<?php 

class Roles_permissions extends CI_Model {
      


    function retrieveUsersRolesPermissions(){

        $users = $this->db->select('id,username,email,fname,lname')->get('admin_user')->result();
        
        foreach( $users as $user ){
            // get the user roles and permissions
            $roles = $this->db->get_where('roles_users', [ 'users_id' => $user->id ])->result();
            
            $roles_arr = [];
            
            foreach( $roles as $r ){
                
                $role = $this->db->select('id,label')->get_where('roles', [ 'id' => $r->roles_id ])->row();
                
                $roles_arr[] = $role;
                
                // attach the role and the permissions
            }
            
            $user->roles = $roles_arr;

            // get the user permissions and permissions
            $permissions = $this->db->get_where('perms_users', [ 'users_id' => $user->id ])->result();
            
            $permissions_arr = [];
            
            foreach( $permissions as $p ){
                
                $permission = $this->db->select('id,label')->get_where('permissions', [ 'id' => $p->perms_id ])->row();
                
                $permissions_arr[] = $permission;
                
                // attach the role and the permissions
            }
            
            $user->permissions = $permissions_arr;
            
        }
        
        return $users;
    }
    
    function retrieveUsersRoles(){
        
        $users = $this->db->select('id,username,email,fname,lname')->get('admin_user')->result();
        
        foreach( $users as $user ){
            // get the user roles and permissions
            $roles = $this->db->get_where('roles_users', [ 'users_id' => $user->id ])->result();
            
            $roles_arr = [];
            
            foreach( $roles as $r ){
                
                $role = $this->db->select('id,label')->get_where('roles', [ 'id' => $r->roles_id ])->row();
                
                $roles_arr[] = $role;
                
                // attach the role and the permissions
            }
            
            $user->roles = $roles_arr;
            
        }
        
        return $users;
        
    }
    

    function getRolesByUser($id){
        
        
        $roles = $this->db->get_where('roles_users', [ 'users_id' => $id] )->result();
        
        $roles_arr = [];
        
        foreach( $roles as $r ){
            
            $roles_arr[] = $this->db->get_where('roles', [ 'id' => $r->roles_id ])->row();
        }
        
        return $roles_arr;
        
    }
    
    function getRoles(){
        
        return $this->db->get('roles')->result();
        
    }
    
    function attachUserRole($users_id,$roles_id){
        
        if( $this->db->get_where('roles_users', [ 'users_id' => $users_id, 'roles_id' => $roles_id])->num_rows() > 0 ){
            
            return false;
            
        }else{
            
            $this->db->insert('roles_users', [ 'users_id' => $users_id, 'roles_id' => $roles_id ] );
            
            return $this->db->get_where('roles', [ 'id' => $roles_id ] )->row();
        }
        
    }
    
    function removeUserRole($users_id,$roles_id){
        
        if( $this->db->get_where('roles_users', [ 'users_id' => $users_id, 'roles_id' => $roles_id])->num_rows() > 0 ){
            
            $this->db->delete('roles_users', [ 'users_id' => $users_id, 'roles_id' => $roles_id] );
            
            return true;
            
        }else{
            
            return false;
        }
        
        
    }

    
    function insertRole($data){
        
        $this->db->insert('roles',$data);
        
        return $this->db->get_where('roles', [ 'id' => $this->db->insert_id() ] )->row();
        
    }
    
    function deleteRole($id){
        
        if( !$this->getRoleById($id) ){
            return false;
        }else{
           
             $this->db->where('id',$id);
            $this->db->delete('roles');
            return $id;
        }
        
    }


    function getRoleById($id){

        if( $this->db->get_where('roles', [ 'id' => $id ])->num_rows() > 0 ){
            $result = $this->db->get_where('roles', [ 'id' => $id ])->row();
        }else{
            $result = false;
        }
        return $result;
    }

    function updateRole($id,$data){

        if( !$this->getRoleById($id) ){
            $msg = false;
        }else{
            $this->db->where('id',$id);
            $this->db->update('roles',$data);

            $msg = $this->getRoleById($id);
        }
        return $msg;
    }


    // ###################### P E R M I S S I O N S
    function retrieveUsersPermissions(){
        
        $users = $this->db->select('id,username,email,fname,lname')->get('admin_user')->result();
        
        foreach( $users as $user ){
            // get the user permissions and permissions
            $permissions = $this->db->get_where('perms_users', [ 'users_id' => $user->id ])->result();
            
            $permissions_arr = [];
            
            foreach( $permissions as $p ){
                
                $permission = $this->db->select('id,label')->get_where('permissions', [ 'id' => $p->perms_id ])->row();
                
                $permissions_arr[] = $permission;
                
                // attach the role and the permissions
            }
            
            $user->permissions = $permissions_arr;
            
        }
        
        return $users;
        
    }


    function insertPermission($data){
        
        $this->db->insert('permissions',$data);
        
        return $this->db->get_where('permissions', [ 'id' => $this->db->insert_id() ] )->row();
        
    }
    
    function deletePermission($id){
        
        if( !$this->getPermissionById($id) ){
            return false;
        }else{
            $this->db->where('id',$id);
            $this->db->delete('permissions');
            
            return $id;
        }
        
    }


    function getPermissionById($id){

        if( $this->db->get_where('permissions', [ 'id' => $id ])->num_rows() > 0 ){
            $result = $this->db->get_where('permissions', [ 'id' => $id ])->row();
        }else{
            $result = false;
        }
        return $result;
    }

    function updatePermission($id,$data){

        if( !$this->getPermissionById($id) ){
            $msg = false;
        }else{
            $this->db->where('id',$id);
            $this->db->update('permissions',$data);

            $msg = $this->getPermissionById($id);
        }
        return $msg;
    }

    function attachUserPermission($users_id,$perms_id){
        
        if( $this->db->get_where('perms_users', [ 'perms_id' => $perms_id, 'users_id' => $users_id ])->num_rows() > 0 ){
            
            return false;
            
        }else{
            
            $this->db->insert('perms_users', [ 'perms_id' => $perms_id, 'users_id' => $users_id ] );
            
            return $this->db->get_where('permissions', [ 'id' => $perms_id ] )->row();
        }
        
    }
    
    function removeUserPermission($users_id,$perms_id){
        
        if( $this->db->get_where('perms_users', [ 'perms_id' => $perms_id, 'users_id' => $users_id])->num_rows() > 0 ){
            
            $this->db->delete('perms_users', [ 'perms_id' => $perms_id, 'users_id' => $users_id] );
            
            return true;
            
        }else{
            
            return false;
        }
        
        
    }

    function getPermissions(){

        return $this->db->get('permissions')->result();
    }

    function getPermissionsByUser($id){
        
        
        $permissions = $this->db->get_where('perms_users', [ 'users_id' => $id] )->result();
        
        $permissions_arr = [];
        
        foreach( $permissions as $p ){
            
            $permissions_arr[] = $this->db->get_where('permissions', [ 'id' => $p->perms_id ])->row();
        }
        
        return $permissions_arr;
        
    }


}
     