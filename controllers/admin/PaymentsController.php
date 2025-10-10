<?php
class PaymentsController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->paymentModel = $this->model('Payment');
    }

    public function index(){
        $payments = $this->paymentModel->getAllPayments();
        $data = [
            'payments' => $payments
        ];
        $this->view('admin/payments/index', $data);
    }
}
?>