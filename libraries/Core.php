<?php
/*
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /area/controller/method/params
 */
class Core {
    protected $currentArea = 'public';
    protected $currentController = 'PagesController'; // Default controller
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();

        // Set Area
        if(isset($url[0]) && in_array($url[0], ['admin', 'user', 'public'])){
            $this->currentArea = $url[0];
            unset($url[0]);
        }

        // Look in controllers for controller
        if(isset($url[1]) && file_exists('../controllers/' . $this->currentArea . '/' . ucwords($url[1]) . 'Controller.php')){
            $this->currentController = ucwords($url[1]) . 'Controller';
            unset($url[1]);
        }

        // Require the controller file
        $controllerFile = '../controllers/' . $this->currentArea . '/' . $this->currentController . '.php';
        if(file_exists($controllerFile)){
            require_once $controllerFile;
        } else {
            // Fallback or error page if controller not found
            die("Controller not found: " . $this->currentController);
        }

        // Instantiate controller class
        if(class_exists($this->currentController)){
             $this->currentController = new $this->currentController;
        } else {
            die("Controller class not found: " . $this->currentController);
        }

        // Check for method in URL
        if(isset($url[2])){
            if(method_exists($this->currentController, $url[2])){
                $this->currentMethod = $url[2];
                unset($url[2]);
            }
        }

        // Get params - Re-index the array
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        // Return a default url if not set
        return ['public', 'pages', 'index'];
    }
}
?>