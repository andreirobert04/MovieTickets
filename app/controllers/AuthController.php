<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../services/AuthService.php';

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login');
    }

    public function registerForm(): void
    {
        $this->render('auth/register');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=auth&action=loginForm');
            exit;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Token CSRF invalid.';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = AuthService::login($email, $password);

        if (!$user) {
            $this->render('auth/login', ['error' => 'Email sau parolă incorecte.']);
            return;
        }

        $_SESSION['user'] = [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        header('Location: /?controller=movie&action=index');
        exit;
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=auth&action=registerForm');
            exit;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Token CSRF invalid.';
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($name === '' || $email === '' || $password === '' || $password !== $password_confirm) {
            $this->render('auth/register', [
                'error' => 'Completează toate câmpurile și asigură-te că parolele coincid.'
            ]);
            return;
        }

        try {
            $user = AuthService::register($name, $email, $password);
        } catch (Exception $e) {
            $this->render('auth/register', ['error' => $e->getMessage()]);
            return;
        }

        $_SESSION['user'] = [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        header('Location: /?controller=movie&action=index');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /?controller=movie&action=index');
        exit;
    }
}
