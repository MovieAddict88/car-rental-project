<?php
class BookingsController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('public/users/login');
        }

        $this->bookingModel = $this->model('Booking');
        $this->carModel = $this->model('Car');
    }

    // List user's bookings
    public function index(){
        $bookings = $this->bookingModel->getBookingsByUserId($_SESSION['user_id']);
        $data = [
            'bookings' => $bookings
        ];
        $this->view('user/bookings/index', $data);
    }

    // Show form to create new booking
    public function new($car_id){
        $car = $this->carModel->getCarById($car_id);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Calculate total price
            $start_date = new DateTime($_POST['start_date']);
            $end_date = new DateTime($_POST['end_date']);
            $days = $end_date->diff($start_date)->days;
            $total_price = $days * $car->price_per_day;

            $data = [
                'car_id' => $car_id,
                'user_id' => $_SESSION['user_id'],
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'total_price' => $total_price,
                'car' => $car,
                'start_date_err' => '',
                'end_date_err' => ''
            ];

            // Validate dates
            if(empty($data['start_date'])){
                $data['start_date_err'] = 'Please select a start date';
            }
            if(empty($data['end_date'])){
                $data['end_date_err'] = 'Please select an end date';
            }
            if($start_date > $end_date){
                 $data['start_date_err'] = 'Start date cannot be after end date';
            }

            if(empty($data['start_date_err']) && empty($data['end_date_err'])){
                if($this->bookingModel->createBooking($data)){
                    // Send notification emails
                    $settingsModel = $this->model('Setting');
                    $settings = $settingsModel->getSettings();
                    $admin_email = $settings['contact_email'];

                    // Email to Admin
                    $subject_admin = 'New Booking Request';
                    $body_admin = 'A new booking has been requested for the car: ' . $car->brand . ' ' . $car->model . '. Please log in to the admin panel to review and confirm.';
                    sendEmail($admin_email, $subject_admin, $body_admin);

                    // Email to User
                    $subject_user = 'Your Booking Request has been Received';
                    $body_user = 'Dear ' . $_SESSION['user_name'] . ',<br>Your booking request for the ' . $car->brand . ' ' . $car->model . ' from ' . $data['start_date'] . ' to ' . $data['end_date'] . ' has been received. You will be notified once it is confirmed by an administrator.';
                    sendEmail($_SESSION['user_email'], $subject_user, $body_user);

                    flash('booking_message', 'Your booking has been requested. Awaiting confirmation.');
                    redirect('user/bookings');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('user/bookings/new', $data);
            }

        } else {
            $data = [
                'car' => $car,
                'start_date_err' => '',
                'end_date_err' => ''
            ];
            $this->view('user/bookings/new', $data);
        }
    }
}
?>