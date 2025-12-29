<?php
session_start();
require_once '../config/database.php';  

$db = Database::getInstance()->getConnection();

// Autoload للـ Controllers و Models
spl_autoload_register(function ($class) {
    $dirs = ['../controllers/', '../models/'];
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// ضبط Error Display للتصحيح
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// الحصول على Action من GET
$action = $_GET['action'] ?? 'login';

// Router
switch($action) {

    // ===== User Actions =====
    case 'login':
        $controller = new UserController($db);
        $controller->login();
        break;

    case 'register':
        $controller = new UserController($db);
        $controller->register();
        break;

    case 'dashboard':
        if (!isset($_SESSION['role'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SESSION['role'] == 'admin') {
            $controller = new AdminController($db);
            $controller->dashboard();
        } else {
            $controller = new UserController($db);
            $controller->dashboard();
        }
        break;

    case 'logout':
        $controller = new UserController($db);
        $controller->logout();
        break;

    case 'book':
        $controller = new TicketController($db);
        $controller->book();
        break;

    case 'receipt':
        $controller = new TicketController($db);
        $ticket_id = intval($_GET['ticket_id'] ?? 0);
        $controller->receipt($ticket_id);
        break;

    case 'schedule':
        $controller = new TrainController($db);
        $controller->schedule();
        break;

    // ===== Admin Actions (كل عملية لوحدها) =====
    case 'recharge':
        $controller = new AdminController($db);
        $controller->recharge();
        break;

    case 'addSchedule':
        $controller = new AdminController($db);
        $controller->addSchedule();
        break;

    case 'delete_ticket':
        $controller = new AdminController($db);
        $controller->deleteTicket();
        break;

    default:
        echo "404 Not Found";
        break;
}
?>
