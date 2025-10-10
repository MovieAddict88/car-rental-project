<?php
class UsersController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->userModel = $this->model('User');
    }

    public function index(){
        $users = $this->userModel->getAllUsers(); // Need to create this method
        $data = [
            'users' => $users
        ];
        $this->view('admin/users/index', $data);
    }

    public function toggle_status($id){
        if($this->userModel->toggleUserStatus($id)){ // Need to create this method
            flash('admin_message', 'User status updated successfully.');
            redirect('admin/users');
        } else {
            die('Something went wrong');
        }
    }

    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->userModel->deleteUser($id)){ // Need to create this method
                flash('admin_message', 'User deleted successfully.');
                redirect('admin/users');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/users');
        }
    }
}
?>