<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
//use PHPMailer\PHPMailer\PHPMailer;
require dirname(__DIR__) . '/vendor/autoload.php';

class TrainUpdate implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn );
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if (isset($data['type']) && $data['type'] == 'update') {
            // Broadcast to all clients
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send(json_encode(['type' => 'update', 'train_id' => $data['train_id'], 'delay' => $data['delay']]));
                }
            }
            echo "Broadcasted update for train {$data['train_id']}\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new TrainUpdate()
        )
    ),
    8080  // Port
);
$server->run();
?>