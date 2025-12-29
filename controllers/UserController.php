<?php
//session_start();
require_once '../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
    }

  public function register() {
    if($_POST) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
       $role = 'user';


        if($this->userModel->create($name, $email, $password, $role)) {  // أضف $role للـ create
            $_SESSION['message'] = 'Registered successfully!';
              header('Location: index.php?action=login');
exit;
   
                 } 
                 else {
            $_SESSION['error'] = 'Registration failed.';
        }
    }
    require_once '../views/register.php';
}

    public function login() {
        if($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->login($email, $password);
            if($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php?action=dashboard');
exit;

            } else {
                $_SESSION['error'] = 'Invalid credentials.';
            }
        }
        require_once '../views/login.php';
    }

    public function dashboard() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ../index.php?action=login');
        }
        if($_SESSION['role'] == 'admin') {
            require_once '../views/admin_dashboard.php';
        } else {
            require_once '../views/user_dashboard.php';
        }
    }

    // Admin: Recharge
    public function recharge() {
        if($_POST && $_SESSION['role'] == 'admin') {
            $user_id = $_POST['user_id'];
            $amount = $_POST['amount'];
            if($this->userModel->recharge($user_id, $amount)) {
                $_SESSION['message'] = 'Recharged successfully!';
            }
        }
        header('Location: ../index.php?action=dashboard');
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
         exit;
    }
}