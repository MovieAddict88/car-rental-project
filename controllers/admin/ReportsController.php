<?php
class ReportsController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->bookingModel = $this->model('Booking');
    }

    public function index(){
        $bookings = [];
        $start_date = '';
        $end_date = '';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            // Add a day to the end date to include all bookings on that day
            $end_date_inclusive = date('Y-m-d', strtotime($end_date . ' +1 day'));
            $bookings = $this->bookingModel->getBookingsByDateRange($start_date, $end_date_inclusive);
        }

        $data = [
            'title' => 'Booking Reports',
            'bookings' => $bookings,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        $this->view('admin/reports/index', $data);
    }

    public function export_csv($start_date, $end_date){
        // Add a day to the end date to include all bookings on that day
        $end_date_inclusive = date('Y-m-d', strtotime($end_date . ' +1 day'));
        $bookings = $this->bookingModel->getBookingsByDateRange($start_date, $end_date_inclusive);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="booking_report_'.$start_date.'_to_'.$end_date.'.csv"');

        $output = fopen('php://output', 'w');

        // Add CSV Header
        fputcsv($output, ['Booking ID', 'User', 'Car', 'Start Date', 'End Date', 'Total Price', 'Status', 'Booked On']);

        // Add CSV Rows
        foreach($bookings as $booking){
            fputcsv($output, [
                $booking->id,
                $booking->user_name,
                $booking->brand . ' ' . $booking->model,
                $booking->start_date,
                $booking->end_date,
                $booking->total_price,
                $booking->status,
                $booking->created_at
            ]);
        }

        fclose($output);
        exit();
    }
}
?>