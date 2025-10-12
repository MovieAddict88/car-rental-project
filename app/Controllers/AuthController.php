<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(app_config('security.session_name'));
            session_start();
        }
    }

    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $this->view('auth/login', ['error' => 'Email and password are required.']);
            return;
        }
        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('auth/login', ['error' => 'Invalid credentials.']);
            return;
        }
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        header('Location: /');
        exit;
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$name || !$email || !$password || !$confirm) {
            $this->view('auth/register', ['error' => 'All fields are required.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', ['error' => 'Invalid email.']);
            return;
        }
        if ($password !== $confirm) {
            $this->view('auth/register', ['error' => 'Passwords do not match.']);
            return;
        }
        if (User::findByEmail($email)) {
            $this->view('auth/register', ['error' => 'Email already registered.']);
            return;
        }
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => app_config('security.password_cost')]);
        User::create($name, $email, $hash);
        $this->view('auth/login', ['success' => 'Registration successful. Please log in.']);
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: /');
        exit;
    }
}
