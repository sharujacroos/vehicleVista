<?php

require_once './database/DbConnector.php';
class Notification
{
    private $notificationId;
    private $senderId;
    private $receiverId;
    private $message;
    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }

    /**
     * @return mixed
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * @param mixed $notificationId
     */
    public function setNotificationId($notificationId): void
    {
        $this->notificationId = $notificationId;
    }

    /**
     * @return mixed
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * @param mixed $senderId
     */
    public function setSenderId($senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return mixed
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * @param mixed $receiverId
     */
    public function setReceiverId($receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


    public function sendNotification(): bool
    {
        $query = "INSERT INTO notification (senderId, receiverId, message) VALUES (?,?,?)";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,$this->senderId);
            $stmt->bindValue(2,$this->receiverId);
            $stmt->bindValue(3,$this->message);
            $rs = $stmt->execute();
            if ($rs){
                return true;
            }else {
                return false;
            }

        }catch (PDOException $ex){
            echo "Error while send Notification<br>.$ex";
            return false;
        }
    }

}