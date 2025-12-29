<?php
require_once '../models/UserModel.php';
require_once '../models/ScheduleModel.php';
require_once '../models/TicketModel.php';
require_once '../models/TrainModel.php';

class AdminController {
    private $userModel;
    private $scheduleModel;
    private $ticketModel;
    private $trainModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
        $this->scheduleModel = new ScheduleModel($db);
        $this->ticketModel = new TicketModel($db);
        $this->trainModel = new TrainModel($db);
    }

    // Dashboard (عرض فقط)
    public function dashboard() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        $users = $this->userModel->getAll();
        $schedules = $this->scheduleModel->getAll();
        $trains = $this->trainModel->getAll();
        $total_tickets = $this->ticketModel->getTicketCount();
        $tickets = $this->ticketModel->getAllTickets();

        require_once '../views/admin_dashboard.php';
    }

    // Recharge User
    public function recharge() {
        if ($_POST && isset($_POST['user_id'], $_POST['amount'])) {
            $user_id = intval($_POST['user_id']);
            $amount = floatval($_POST['amount']);
            if ($user_id > 0 && $amount > 0 && $this->userModel->recharge($user_id, $amount)) {
                $_SESSION['message'] = 'Recharged successfully!';
            } else {
                $_SESSION['error'] = 'Recharge failed.';
            }
        }
        header('Location: index.php?action=dashboard');
        exit;
    }

    // Add Schedule
    public function addSchedule() {
        if ($_POST) {
            $train_id = intval($_POST['train_id']);
            $from = intval($_POST['from_station']);
            $to = intval($_POST['to_station']);
            $departure = str_replace('T', ' ', $_POST['departure_time']) . ':00';
            $arrival = str_replace('T', ' ', $_POST['expected_arrival']) . ':00';

            if ($this->scheduleModel->create($train_id, $from, $to, $departure, $arrival)) {
                $_SESSION['message'] = 'Schedule added successfully.';
            } else {
                $_SESSION['error'] = 'Add schedule failed.';
            }
        }
        header('Location: index.php?action=dashboard');
        exit;
    }

    // Delete Ticket
    public function deleteTicket() {
        if ($_POST && isset($_POST['ticket_id'])) {
            $ticket_id = intval($_POST['ticket_id']);
            if ($ticket_id > 0 && $this->ticketModel->delete($ticket_id)) {
                $_SESSION['message'] = 'Ticket deleted successfully!';
            } else {
                $_SESSION['error'] = 'Delete failed.';
            }
        }
        header('Location: index.php?action=dashboard');
        exit;
    }

  
}

?>
