<?php
//session_start();
require_once '../models/TicketModel.php';
require_once '../models/ScheduleModel.php';
require_once '../models/UserModel.php';

class TicketController {
    private $ticketModel;
    private $scheduleModel;
    private $userModel;

    public function __construct($db) {
        $this->ticketModel = new TicketModel($db);
        $this->scheduleModel = new ScheduleModel($db);
        $this->userModel = new UserModel($db);
    }

    public function book() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../index.php?action=login');
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $balance = $this->userModel->getBalance($user_id);

        if ($_POST) {
            $from = $_POST['from_station'];
            $to = $_POST['to_station'];
            $ticket_type = $_POST['ticket_type'];
            $class = $_POST['class'];
            $schedules = $this->scheduleModel->getByStations($from, $to);
            if (!empty($schedules)) {
                $schedule_id = $schedules[0]['id'];  // First available schedule
                $base_price = 50.00;  // Base price
                $price = $base_price * ($ticket_type == 'return' ? 2 : 1) * ($class == 'first' ? 1.5 : 1);

                if ($balance >= $price) {
                    $ticket_id = $this->ticketModel->book($user_id, $schedule_id, $ticket_type, $class, $price);
                    if ($ticket_id) {
                        $_SESSION['message'] = 'Booking successful! Ticket ID: ' . $ticket_id;
                       header("Location: index.php?action=receipt&ticket_id=$ticket_id");
exit;

                    } else {
                        $_SESSION['error'] = 'Booking failed.';
                    }
                } else {
                    $_SESSION['error'] = 'Insufficient balance.';
                }
            } else {
                $_SESSION['error'] = 'No schedules available for this route.';
            }
        }

        // Load stations for form
        $stations = $this->scheduleModel->getAllStations();  // التحديث هنا

        require_once '../views/book_ticket.php';
    }

    public function receipt($ticket_id) {
        $receipt = $this->ticketModel->getReceipt($ticket_id);
        if (!$receipt) {
            $_SESSION['error'] = 'Ticket not found.';
            header('Location: ../index.php?action=dashboard');
        }
        require_once '../views/view_receipt.php';
    }
}
?>