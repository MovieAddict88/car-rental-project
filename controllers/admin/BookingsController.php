<?php
class BookingsController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->bookingModel = $this->model('Booking');
        $this->userModel = $this->model('User');
        $this->carModel = $this->model('Car');
    }

    public function index(){
        $bookings = $this->bookingModel->getAllBookings();
        $data = [
            'bookings' => $bookings
        ];
        $this->view('admin/bookings/index', $data);
    }

    public function update_status($id, $status){
        $booking = $this->bookingModel->getBookingById($id);

        if(!$booking){
            die('Booking not found');
        }

        if($this->bookingModel->updateBookingStatus($id, $status)){
            // Update car availability based on status
            if($status == 'Confirmed'){
                $this->carModel->updateAvailability($booking->car_id, 0); // Set to unavailable

                // Send confirmation email to user
                $user = $this->userModel->getUserById($booking->user_id);
                $car = $this->carModel->getCarById($booking->car_id);
                $subject = 'Your Booking is Confirmed!';
                $body = 'Dear ' . $user->name . ',<br>Your booking for the ' . $car->brand . ' ' . $car->model . ' from ' . $booking->start_date . ' to ' . $booking->end_date . ' has been confirmed. We look forward to seeing you!';
                sendEmail($user->email, $subject, $body);

            } elseif ($status == 'Completed' || $status == 'Cancelled'){
                $this->carModel->updateAvailability($booking->car_id, 1); // Set to available
            }

            flash('admin_message', 'Booking status updated successfully.');
            redirect('admin/bookings');
        } else {
            die('Something went wrong');
        }
    }
}
?>