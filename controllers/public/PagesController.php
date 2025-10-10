<?php
class PagesController extends Controller {
    public function __construct(){
        $this->carModel = $this->model('Car');
    }

    public function index(){
        // Get cars
        $cars = $this->carModel->getCars();

        $data = [
            'title' => 'Welcome to CRMS',
            'description' => 'The best place to rent your dream car.',
            'cars' => $cars
        ];

        $this->view('public/index', $data);
    }

    public function about(){
        $data = [
            'title' => 'About Us',
            'description' => 'We are a premier car rental service dedicated to providing you with the best experience.'
        ];

        $this->view('public/about', $data);
    }

    public function car_details($id){
        $car = $this->carModel->getCarById($id);
        $data = [
            'car' => $car
        ];
        $this->view('public/car_details', $data);
    }
}
?>