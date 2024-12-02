<?php
require_once __DIR__ . '/../database/DbConnector.php';

class Admin
{
    private $adminId;
    private $userId;
    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }

    public function getAdminId()
    {
        return $this->adminId;
    }

    public function setAdminId($adminId): void
    {
        $this->adminId = $adminId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function approveServiceStation($stationId, $membership): bool
    {
        $query = "UPDATE servicestation SET status = ?,membershipRenewalDate = ? WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, 1);
            $stmt->bindValue(2, $membership);
            $stmt->bindValue(3, $stationId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while approve service station<br>.$ex";
            return false;
        }
    }

    public function viewAllServiceStations(): false|array
    {
        $query = "SELECT * FROM servicestation";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while view all service stations <br>.$e";
            return false;
        }
    }

    public function viewAllServiceStationRequest(): array
    {
        $query = "SELECT * FROM servicestation WHERE status = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, 0);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            } else {
                return [];
            }
        } catch (Exception $ex) {
            echo "Error while view all service stations Request<br>.$e";
            return [];
        }
    }

    public function deleteServiceStation($stationId): bool
    {
        $query = "DELETE FROM servicestation WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,$stationId);
            $stmt->execute();
            $rs = $stmt->rowCount();
            if ($rs > 0){
                return true;
            }else {
                return false;
            }
        }catch (PDOException $ex){
            echo "Error while delete Service Station<br>.$ex";
            return false;
        }
    }

    public function deleteUser($userId): bool
    {
        $query = "DELETE FROM user WHERE userId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,$userId);
            $stmt->execute();
            $rs = $stmt->rowCount();
            if ($rs > 0){
                return true;
            }else {
                return false;
            }
        }catch (PDOException $ex){
            echo "Error while delete user<br>.$ex";
            return false;
        }
    }

    public function viewAllUsers(): false|array
    {
        $query = "SELECT u.*, c.* FROM user AS u JOIN customer AS c ON u.userId = c.userId";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while view all users<br>.$ex";
            return false;
        }
    }

    public function viewAllBookings(): false|array
    {
        $query = "SELECT * FROM booking";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rs) {
                return $rs;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while get All bookings<br>.$ex";
            return false;
        }
    }

    public function countStation()
    {
        $query = 'SELECT COUNT(*) AS countStation FROM servicestation WHERE status = ?';
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,1);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs['countStation'];
            } else {
                return 0;
            }
        } catch (PDOException $ex) {
            echo "Errror while count service stations<br>.$ex";
            return 0;
        }
    }

    public function countCustomers()
    {
        $query = 'SELECT COUNT(*) AS countUser FROM customer';
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs['countUser'];
            } else {
                return 0;
            }
        } catch (PDOException $ex) {
            echo "Errror while count Customers<br>.$ex";
            return 0;
        }
    }

    public function countBooking()
    {
        $query = 'SELECT COUNT(*) AS contBooking FROM booking';
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs['contBooking'];
            } else {
                return 0;
            }
        } catch (PDOException $ex) {
            echo "Errror while count booking<br>.$ex";
            return 0;
        }
    }
}
