<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Flash message helper
// EXAMPLE - flash('register_success', 'You are now registered', 'alert alert-danger');
// DISPLAY IN VIEW - echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}

// Check if user is logged in
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}

function isAdmin(){
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
        return true;
    }
    return false;
}

// User session creation
function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_role'] = $user->role;
}

// User logout
function logout(){
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
}
?>