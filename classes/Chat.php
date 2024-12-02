<?php
require_once __DIR__ . '/../database/DbConnector.php';

use ElephantIO\Client;


class Chat
{
    private $chatId;
    private $senderId;
    private $receiverId;
    private $message;
    private $timeStamp;

    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }

    public function getChatId()
    {
        return $this->chatId;
    }

    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function setSenderId($senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function setReceiverId($receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    public function setTimeStamp($timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function sendMessage(): bool
    {
        $query = "INSERT INTO chat (senderId, receiverId, message, timeStamp) VALUES (?,?,?,?)";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->senderId);
            $stmt->bindValue(2, $this->receiverId);
            $stmt->bindValue(3, $this->message);
            $stmt->bindValue(4, $this->timeStamp);
            $rs = $stmt->execute();

            if ($rs) {
                $socket = new \ElephantIO\Client(new \ElephantIO\Engine\SocketIO\Version2X('http://localhost:3000'));
                $socket->initialize();
                $socket->emit('newChat', ['message' => $this->message]);
                $socket->close();
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while sending message: " . $ex->getMessage();
            return false;
        }
    }


    public function getChatParticipants($userId): array
    {
        $query = "SELECT DISTINCT senderId, receiverId FROM chat WHERE senderId = ? OR receiverId = ?";
        $participants = [];
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $userId);
            $stmt->bindValue(2, $userId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $participants[] = $row['senderId'] == $userId ? $row['receiverId'] : $row['senderId'];
            }
            return array_unique($participants);
        } catch (Exception $ex) {
            echo "Error while retrieving chat participants.<br>" . $ex;
            return [];
        }
    }

    public function viewChatHistory($senderId, $receiverId): false|array
    {
        $query = "SELECT * FROM chat WHERE (senderId = ? AND receiverId = ?) OR (senderId = ? AND receiverId = ?) ORDER BY timeStamp ASC";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $senderId);
            $stmt->bindValue(2, $receiverId);
            $stmt->bindValue(3, $receiverId);
            $stmt->bindValue(4, $senderId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo "Error while retrieving chat history.<br>" . $ex;
            return [];
        }
    }
}