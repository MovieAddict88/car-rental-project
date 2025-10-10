<?php
class ProfileController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('public/users/login');
        }
        $this->userModel = $this->model('User');
    }

    public function index(){
        // Get user info from DB
        $user = $this->userModel->findUserByEmail($_SESSION['user_email']);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'user' => $user,
                'name_err' => '',
                'email_err' => ''
            ];

            // Validate data
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            // If no errors
            if(empty($data['name_err']) && empty($data['email_err'])){
                if($this->userModel->updateProfile($data)){
                    // Update session variables
                    $_SESSION['user_name'] = $data['name'];
                    $_SESSION['user_email'] = $data['email'];
                    flash('profile_message', 'Profile updated successfully');
                    redirect('user/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('user/profile/index', $data);
            }

        } else {
            $data = [
                'user' => $user,
                'name_err' => '',
                'email_err' => ''
            ];
            $this->view('user/profile/index', $data);
        }
    }

    public function change_password(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $_SESSION['user_id'],
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate current password
            $user = $this->userModel->findUserByEmail($_SESSION['user_email']);
            if(!password_verify($data['current_password'], $user->password)){
                $data['current_password_err'] = 'Incorrect current password';
            }

            // Validate new password
            if(empty($data['new_password'])){
                $data['new_password_err'] = 'Please enter a new password';
            } elseif(strlen($data['new_password']) < 6){
                $data['new_password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if($data['new_password'] != $data['confirm_password']){
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            if(empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])){
                // Hash new password
                $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                if($this->userModel->changePassword($data)){
                    flash('profile_message', 'Password changed successfully');
                    redirect('user/profile');
                } else {
                     die('Something went wrong');
                }
            } else {
                // Need to reload the main profile view with password errors
                $user = $this->userModel->findUserByEmail($_SESSION['user_email']);
                $profile_data = ['user' => $user, 'name_err' => '', 'email_err' => ''];
                $this->view('user/profile/index', array_merge($profile_data, $data));
            }

        } else {
            redirect('user/profile');
        }
    }
}
?>