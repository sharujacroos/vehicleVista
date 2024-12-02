<?php
require_once __DIR__ . '/../database/DbConnector.php';

class Services
{
    private $serviceId;
    private $employeeId;
    private $stationId;
    private $serviceName;
    private $vehicleType;
    private $serviceCharge;
    private $duration;

    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function setServiceId($serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function setEmployeeId($employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    public function getStationId()
    {
        return $this->stationId;
    }

    public function setStationId($stationId): void
    {
        $this->stationId = $stationId;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function setServiceName($serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    public function setVehicleType($vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    public function getServiceCharge()
    {
        return $this->serviceCharge;
    }

    public function setServiceCharge($serviceCharge): void
    {
        $this->serviceCharge = $serviceCharge;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    public function addService(): bool
    {
        $query = "INSERT INTO services( stationId, serviceName, vehicleType, serviceCharge, duration)"
            . "VALUES (?,?,?,?,?)";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->bindValue(2, $this->serviceName);
            $stmt->bindValue(3, $this->vehicleType);
            $stmt->bindValue(4, $this->serviceCharge);
            $stmt->bindValue(5, $this->duration);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "error while add service<br>.$ex";
            return false;
        }
    }

    public function updateService(): bool
    {
        $query = "UPDATE services SET serviceName=?, vehicleType=?, serviceCharge=?, duration=? WHERE serviceId=?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->serviceName);
            $stmt->bindValue(2, $this->vehicleType);
            $stmt->bindValue(3, $this->serviceCharge);
            $stmt->bindValue(4, $this->duration);
            $stmt->bindValue(5, $this->serviceId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "error while update service<br>.$ex";
            return false;
        }
    }

    public function removeService(): bool
    {
        $query = "DELETE FROM services WHERE serviceId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->serviceId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "error while remove service<br>.$ex";
            return false;
        }
    }
 public function findStationByUserID($userId){
        $query = "SELECT stationId FROM servicestation WHERE ownerId = ?";
     try {
         $con = $this->db->getConnection();
         $stmt = $con->prepare($query);
         $stmt->bindValue(1, $userId);
         $stmt->execute();
         $rs = $stmt->fetch(PDO::FETCH_ASSOC);
         if ($rs){
             return $rs['stationId'];
         }else {
             return 0;
         }
     }catch (PDOException $ex){
         echo "Error while find station by userId<br>.$ex";
         return 0;
     }
 }

    public function findEmployeeByUserID($userId){
        $query = "SELECT employeeId FROM employee WHERE userId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $userId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs){
                return $rs['employeeId'];
            }else {
                return 0;
            }
        }catch (PDOException $ex){
            echo "Error while find employee by userId<br>.$ex";
            return 0;
        }
    }
}