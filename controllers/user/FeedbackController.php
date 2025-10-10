<?php
class FeedbackController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('public/users/login');
        }
        $this->feedbackModel = $this->model('Feedback');
    }

    public function index(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'message_err' => ''
            ];

            // Validate message
            if(empty($data['message'])){
                $data['message_err'] = 'Please enter a message';
            }

            if(empty($data['message_err'])){
                if($this->feedbackModel->addFeedback($data)){
                    flash('feedback_success', 'Thank you for your feedback!');
                    redirect('user/feedback');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('user/feedback/index', $data);
            }

        } else {
            $data = [
                'message' => '',
                'message_err' => ''
            ];
            $this->view('user/feedback/index', $data);
        }
    }
}
?>