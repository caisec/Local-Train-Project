<?php
//session_start();
require_once '../models/ScheduleModel.php';
require_once '../models/RealTimeUpdateModel.php';
require_once '../models/TrainModel.php';

class TrainController {
    private $scheduleModel;
    private $realTimeModel;
    private $trainModel;

    public function __construct($db) {
        $this->scheduleModel = new ScheduleModel($db);
        $this->realTimeModel = new RealTimeUpdateModel($db);
        $this->trainModel = new TrainModel($db);
    }

    public function schedule() {
        $schedules = $this->scheduleModel->getAll();
        foreach ($schedules as &$sch) {
            $latest_update = $this->realTimeModel->getLatestByTrain($sch['train_id']);
            $previous_delay = $latest_update['delay_minutes'] ?? 0;
            $time_of_day = (int)date('H', strtotime($sch['departure_time']));
            
            $predicted_delay = $this->realTimeModel->predictDelay($sch['train_id'], $previous_delay, $time_of_day);
            $sch['predicted_arrival'] = date('Y-m-d H:i:s', strtotime($sch['expected_arrival']) + ($predicted_delay * 60));
        }
        $trains = $this->trainModel->getAll();  // For admin forms
        require_once '../views/train_schedule.php';
    }

    public function updateRealTime() {  // AJAX endpoint
        if ($_POST && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            $train_id = $_POST['train_id'];
            $station_id = $_POST['station_id'];
            $actual_arrival = $_POST['actual_arrival'] ?? null;
            $delay = $_POST['delay'] ?? 0;
            $this->realTimeModel->update($train_id, $station_id, null, $actual_arrival, $delay);
            echo json_encode(['status' => 'updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        }
    }

    public function getRealTimeUpdates() {  // For AJAX polling (backup for WS)
        header('Content-Type: application/json');
        $train_id = $_GET['train_id'] ?? 1;
        $update = $this->realTimeModel->getLatestByTrain($train_id);
        if ($update) {
            $previous_delay = $update['delay_minutes'];
            $time_of_day = (int)date('H');
            $predicted = $this->realTimeModel->predictDelay($train_id, $previous_delay, $time_of_day);
            $update['predicted_next'] = 'Predicted delay: ' . $predicted . ' minutes';
        }
        echo json_encode($update ?? []);
    }
}
?>