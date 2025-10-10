<?php
class CarsController extends Controller {
    public function __construct(){
        if(!isAdmin()){
            redirect('public/users/login');
        }
        $this->carModel = $this->model('Car');
    }

    public function index(){
        $cars = $this->carModel->getAllCars(); // Need to create this method
        $data = [
            'cars' => $cars
        ];
        $this->view('admin/cars/index', $data);
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'type' => trim($_POST['type']),
                'price_per_day' => trim($_POST['price_per_day']),
                'image' => $_FILES['image']['name'],
                'image_tmp' => $_FILES['image']['tmp_name'],
                'brand_err' => '', 'model_err' => '', 'price_err' => ''
            ];

            // Image upload
            $upload_dir = APPROOT . '/public/assets/images/cars/';
            move_uploaded_file($data['image_tmp'], $upload_dir . $data['image']);

            // Validate data
            if(empty($data['brand'])){ $data['brand_err'] = 'Please enter brand'; }
            if(empty($data['model'])){ $data['model_err'] = 'Please enter model'; }
            if(empty($data['price_per_day'])){ $data['price_err'] = 'Please enter price'; }

            if(empty($data['brand_err']) && empty($data['model_err']) && empty($data['price_err'])){
                if($this->carModel->addCar($data)){
                    flash('admin_message', 'Car added successfully');
                    redirect('admin/cars');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/cars/add', $data);
            }
        } else {
            $data = ['brand' => '', 'model' => '', 'type' => '', 'price_per_day' => '', 'image' => '', 'brand_err' => '', 'model_err' => '', 'price_err' => ''];
            $this->view('admin/cars/add', $data);
        }
    }

    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'type' => trim($_POST['type']),
                'price_per_day' => trim($_POST['price_per_day']),
                'availability' => isset($_POST['availability']) ? 1 : 0,
                'brand_err' => '', 'model_err' => '', 'price_err' => ''
            ];

            // Validate data
            if(empty($data['brand'])){ $data['brand_err'] = 'Please enter brand'; }
            if(empty($data['model'])){ $data['model_err'] = 'Please enter model'; }
            if(empty($data['price_per_day'])){ $data['price_err'] = 'Please enter price'; }

            if(empty($data['brand_err']) && empty($data['model_err']) && empty($data['price_err'])){
                if($this->carModel->updateCar($data)){
                    flash('admin_message', 'Car updated successfully');
                    redirect('admin/cars');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/cars/edit', $data);
            }
        } else {
            $car = $this->carModel->getCarById($id);
            $data = ['car' => $car, 'brand_err' => '', 'model_err' => '', 'price_err' => ''];
            $this->view('admin/cars/edit', $data);
        }
    }

    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->carModel->deleteCar($id)){
                flash('admin_message', 'Car removed successfully');
                redirect('admin/cars');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/cars');
        }
    }
}
?>