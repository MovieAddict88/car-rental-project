<?php
class SettingsController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->settingModel = $this->model('Setting');
    }

    public function index(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $settings_data = [
                'website_name' => $_POST['website_name'],
                'contact_email' => $_POST['contact_email'],
                'contact_phone' => $_POST['contact_phone'],
                'paypal_client_id' => $_POST['paypal_client_id'],
                'stripe_secret_key' => $_POST['stripe_secret_key'],
                'smtp_host' => $_POST['smtp_host'],
                'smtp_user' => $_POST['smtp_user'],
                'smtp_pass' => $_POST['smtp_pass'],
                'smtp_port' => $_POST['smtp_port'],
                'smtp_secure' => $_POST['smtp_secure']
            ];

            if($this->settingModel->updateSettings($settings_data)){
                flash('admin_message', 'Settings updated successfully.');
                redirect('admin/settings');
            } else {
                die('Something went wrong');
            }
        } else {
            $settings = $this->settingModel->getSettings();
            $data = [
                'settings' => $settings
            ];
            $this->view('admin/settings/index', $data);
        }
    }
}
?>