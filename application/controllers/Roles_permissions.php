<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions extends CI_Controller {

	public function roles_permissions(){
    

        $this->load->model('roles_permissions');
        
        $header_data = $this->header_data;
        $data['roles'] = $this->roles_permissions->getRoles();
        $data['permissions'] = $this->roles_permissions->getPermissions();
        $data['roles_permissions'] = $this->roles_permissions->retrieveUsersRolesPermissions();
        $footer_data['page'] = 'roles_permissions'; 
        
        $this->load->view('elements/header', $header_data);
        $this->load->view('system/roles_permissions', $data);
        $this->load->view('elements/footer',$footer_data);
        
    }
    public function getusersroles(){
        $this->load->model('roles_permissions');
        header('Content-type: application/json');
        
        echo json_encode([ 'success' => true, 'msg' => $this->roles_permissions->retrieveUsersRolesPermissions() ]);
        
        return;
    }
    public function get_user_roles(){
        header('Content-type: application/json');
        
        $this->load->model('roles_permissions');
        
        $id = $this->input->get('id');
        
        $user_roles = $this->roles_permissions->getRolesByUser($id);
        $roles = $this->roles_permissions->getRoles();
        
        echo json_encode([ 'success' => true, 'data' => $user_roles, 'roles' => $roles ]);
        
        return;
        
    }
    
  
    public function attach_user_role(){
        header('Content-type: application/json');
        $this->load->model('roles_permissions');
        
        $roles_id = $this->input->post('roles_id');
        $users_id = $this->input->post('users_id');
        
        $result = $this->roles_permissions->attachUserRole($users_id,$roles_id);
        $success = false;
        if( !$result ){
            $msg = 'Already been attached';
        }else{
            $msg = $result;
            $success = true;
        }
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
    }
    
    public function remove_user_role(){
        header('Content-type: application/json');
        $this->load->model('roles_permissions');
        
        $roles_id = $this->input->post('roles_id');
        $users_id = $this->input->post('users_id');
        
        $result = $this->roles_permissions->removeUserRole($users_id,$roles_id);
        $success = false;
        if( !$result ){
            $msg = 'Role already been removed';
        }else{
            $msg = $result;
            $success = true;
        }
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
    }


    // ------------------------------ R O L E S ------------------------------

    public function getroles(){
        $this->load->model('roles_permissions');
        header('Content-type: application/json');

        $result = $this->roles_permissions->getRoles();

        echo json_encode([ 'success' => true, 'msg' => $result ]);

        return;
    }
   
    public function add_role(){
        
        $this->load->model('roles_permissions');
        $this->load->library('form_validation');
        
        header('Content-type: application/json');
        
        
        $success = false;
        $this->form_validation->set_rules('label', 'label', 'required|is_unique[roles.label]');

        if ($this->form_validation->run() != FALSE){
             $data = [
                'label' => $this->input->post('label'),
                'description' => $this->input->post('description')
            ];
            
            $result = $this->roles_permissions->insertRole($data);
            if( !$result ){
                
                $msg = 'An error occured, unable to create new role.';
                
            }else{
                $msg = $result;
                $success = true;
            }
        }else{
            $msg = validation_errors();
        }
        
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
    }
    public function delete_role(){
        $this->load->model('roles_permissions');
        header('Content-type: application/json');
        $id = $this->input->get('id');
        
        $result = $this->roles_permissions->deleteRole($id);
        $success = false;
        
        if( !$result ){
                
            $msg = 'An error occured, unable to delete.';
            
        }else{
            $msg = $result;
            $success = true;
        }
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
        
    }
    public function edit_role(){
        
        $this->load->model('roles_permissions');
        $this->load->library('form_validation');
        
        header('Content-type: application/json');
        
        $id =  $this->input->post('id');
        $success = false;
        $errors = [];
        
        $old_role = $this->roles_permissions->getRoleById($id);
        
       if( !$old_role){
            $msg = 'An error occured, role does not exist';
       }else{
            if( $old_role->label != $this->input->post('label') ){
                $this->form_validation->set_rules('label', 'label', 'required|is_unique[roles.label]');    
            }else{
                 $this->form_validation->set_rules('label', 'label', 'required');    
            }

            if( count($errors) > 0 ){

                $msg = '';
                foreach( $errors as $err ){
                    $msg.='<div>'.$err.'</div>';
                }
            }else{
                if ($this->form_validation->run() != FALSE){

                    $data = [
                        'label' => $this->input->post('label'),
                        'description' => $this->input->post('description'),
                    ];

                    $result = $this->roles_permissions->updateRole($id,$data);
                    if( !$result ){
                        
                        $msg = 'Role does not exist.';
                        
                    }else{
                        $msg = $result;
                        $success = true;
                    }
                }else{
                    $msg = form_error('label');
                }
            }
       }
        
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
    }

    public function getrole(){

        $this->load->model('roles_permissions');
        header('Content-type: application/json');

        $id = $this->input->get('id');

        $result = $this->roles_permissions->getRoleById($id);
        $success = false;

        if( !$result ){
            $msg = 'An error occured, role does not exist';
        }else{
            $msg = $result;
            $success = true;
        }

        echo json_encode([ 'success' => $success, 'msg' => $msg ]);

        return;
    }



    // ------------------------------ P E R M I S S I O N S ------------------------------

    public function getpermissions(){
        $this->load->model('roles_permissions');
        header('Content-type: application/json');

        $result = $this->roles_permissions->getPermissions();

        echo json_encode([ 'success' => true, 'msg' => $result ]);

        return;
    }

    public function getpermission(){

        $this->load->model('roles_permissions');
        header('Content-type: application/json');

        $id = $this->input->get('id');

        $result = $this->roles_permissions->getPermissionById($id);
        $success = false;

        if( !$result ){
            $msg = 'An error occured, permission does not exist';
        }else{
            $msg = $result;
            $success = true;
        }

        echo json_encode([ 'success' => $success, 'msg' => $msg ]);

        return;
    }
    public function get_user_permissions(){
        header('Content-type: application/json');
        
        $this->load->model('roles_permissions');
        
        $users_id = $this->input->get('users_id');
        
        $user_permissions = $this->roles_permissions->getPermissionsByUser($users_id);
        $permissions = $this->roles_permissions->getPermissions();
        
        echo json_encode([ 'success' => true, 'data' => $user_permissions, 'permissions' => $permissions ]);
        
        return;
        
    }
    

    public function add_permission(){
        
        $this->load->model('roles_permissions');
        $this->load->library('form_validation');
        
        header('Content-type: application/json');
        
        
        $success = false;
        $this->form_validation->set_rules('label', 'label', 'required|is_unique[permissions.label]');

        if ($this->form_validation->run() != FALSE){
             $data = [
                'label' => $this->input->post('label'),
                'description' => $this->input->post('description')
            ];
            
            $result = $this->roles_permissions->insertPermission($data);
            if( !$result ){
                
                $msg = 'An error occured, unable to create new permission.';
                
            }else{
                $msg = $result;
                $success = true;
            }
        }else{
            $msg = validation_errors();
        }
        
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
    }
    public function delete_permission(){
        $this->load->model('roles_permissions');
        header('Content-type: application/json');
        $id = $this->input->get('id');
        
        $result = $this->roles_permissions->deletePermission($id);
        $success = false;
        
        if( !$result ){
                
            $msg = 'An error occured, unable to delete.';
            
        }else{
            $msg = $result;
            $success = true;
        }
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
        
    }
    public function edit_permission(){
        
        $this->load->model('roles_permissions');
        $this->load->library('form_validation');
        
        header('Content-type: application/json');
        
        $id =  $this->input->post('id');
        $success = false;
        $errors = [];
        
        $old_permission = $this->roles_permissions->getPermissionById($id);
        
       if( !$old_permission){
            $msg = 'An error occured, permission does not exist';
       }else{
            if( $old_permission->label != $this->input->post('label') ){
                $this->form_validation->set_rules('label', 'label', 'required|is_unique[permissions.label]');    
            }else{
                 $this->form_validation->set_rules('label', 'label', 'required');    
            }

            if( count($errors) > 0 ){

                $msg = '';
                foreach( $errors as $err ){
                    $msg.='<div>'.$err.'</div>';
                }
            }else{
                if ($this->form_validation->run() != FALSE){

                    $data = [
                        'label' => $this->input->post('label'),
                        'description' => $this->input->post('description'),
                    ];

                    $result = $this->roles_permissions->updatePermission($id,$data);
                    if( !$result ){
                        
                        $msg = 'Permission does not exist.';
                        
                    }else{
                        $msg = $result;
                        $success = true;
                    }
                }else{
                    $msg = form_error('label');
                }
            }
       }
        
        
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
        
        return;
    }

    public function attach_user_permission(){
        header('Content-type: application/json');
        $this->load->model('roles_permissions');
        
        $users_id = $this->input->post('users_id');
        $perms_id = $this->input->post('perms_id');
        
        $result = $this->roles_permissions->attachUserPermission($users_id,$perms_id);
        
        $success = false;
        if( !$result ){
            $msg = 'Already been attached';
        }else{
            $msg = $result;
            $success = true;
        }
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
    }
    
    public function remove_user_permission(){
        header('Content-type: application/json');
        $this->load->model('roles_permissions');
        
        $users_id = $this->input->post('users_id');
        $perms_id = $this->input->post('perms_id');
        
        $result = $this->roles_permissions->removeUserPermission($users_id,$perms_id);
        $success = false;
        if( !$result ){
            $msg = 'Permission already been removed';
        }else{
            $msg = $result;
            $success = true;
        }
        echo json_encode([ 'success' => $success, 'msg' => $msg ]);
    }
    
    public function test(){
         $this->load->model('roles_permissions');
        echo '<pre>';
        var_dump($this->roles_permissions->retrieveUsersRolesPermissions());
    }
}
