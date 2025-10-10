<?php
class DashboardController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->adminModel = $this->model('Admin');
    }

    public function index(){
        $totalUsers = $this->adminModel->getTotalUsers();
        $totalCars = $this->adminModel->getTotalCars();
        $totalBookings = $this->adminModel->getTotalBookings();
        $totalRevenue = $this->adminModel->getTotalRevenue();

        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $totalUsers,
            'total_cars' => $totalCars,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue
        ];
        $this->view('admin/dashboard', $data);
    }
}
?>