<?php
class FeedbackController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->feedbackModel = $this->model('Feedback');
    }

    public function index(){
        $feedback = $this->feedbackModel->getAllFeedback();
        $data = [
            'title' => 'User Feedback',
            'feedback' => $feedback
        ];
        $this->view('admin/feedback/index', $data);
    }

    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->feedbackModel->deleteFeedback($id)){
                flash('admin_message', 'Feedback deleted successfully.');
                redirect('admin/feedback');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/feedback');
        }
    }
}
?>