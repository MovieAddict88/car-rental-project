<?php
class PaymentsController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('public/users/login');
        }
        $this->bookingModel = $this->model('Booking');
    }

    public function pay($booking_id){
        $booking = $this->bookingModel->getBookingById($booking_id);

        // Security check
        if($booking->user_id != $_SESSION['user_id']){
            redirect('user/bookings');
        }

        // Don't allow payment for bookings that aren't confirmed
        if($booking->status != 'Confirmed'){
             flash('booking_message', 'You can only pay for confirmed bookings.', 'alert alert-danger');
             redirect('user/bookings');
        }

        $data = [
            'booking' => $booking
        ];

        $this->view('user/payments/pay', $data);
    }
}
?>