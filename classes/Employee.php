<?php
require_once __DIR__ . '/../database/DbConnector.php';

class Employee
{
    private $employeeId;
    private $stationId;
    private $userId;
    private $services;
    private $arrivalTime;
    private $departureTime;

    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setServices($services): void
    {
        $this->services = $services;
    }

    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime($arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    public function setDepartureTime($departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    public function updateProfile(): bool
    {
        $query = "UPDATE employee SET services=?, arrivalTime=?, departureTime=? WHERE employeeId=?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->services);
            $stmt->bindValue(2, $this->arrivalTime);
            $stmt->bindValue(3, $this->departureTime);
            $stmt->bindValue(4, $this->employeeId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error While update profile<br>.$ex";
            return false;
        }
    }

}
