<?php
require_once __DIR__ . "/../framework/BaseController.php";

class LoginController extends BaseSuperTwigController {
    public $template = "login.twig";

    public function get(array $context)
    {
        parent::get($context);
    }

    public function post(array $context)
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $query->execute(['username' => $username, 'password' => $password]);
        $user = $query->fetch();
        
        if ($user) {
            $_SESSION['is_logged'] = true;
            header("Location: /");
            exit;
        } else {
            $_SESSION['error'] = 'Неверный логин или пароль';
            header("Location: /login");
            exit;
        }
    }
}