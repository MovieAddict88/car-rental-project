<?php
class DashboardController extends Controller {
    public function __construct(){
        // Protect page for logged in users only
        if(!isLoggedIn()){
            redirect('public/users/login');
        }
    }

    public function index(){
        $data = [
            'title' => 'User Dashboard',
            'user_name' => $_SESSION['user_name']
        ];
        $this->view('user/dashboard', $data);
    }
}
?>