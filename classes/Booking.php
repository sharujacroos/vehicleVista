<?php
require_once __DIR__ . '/../database/DbConnector.php';

class Booking
{
    private $bookId;
    private $customerId;
    private $stationId;
    private $employeeId;
    private $vehicleType;
    private $vehicleNumber;
    private $driverName;
    private $mobileNumber;
    private $services;
    private $status;
    private $rating;
    private $date;
    private $time;

    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }

    public function getBookId()
    {
        return $this->bookId;
    }

    public function setBookId($bookId): void
    {
        $this->bookId = $bookId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getStationId()
    {
        return $this->stationId;
    }

    public function setStationId($stationId): void
    {
        $this->stationId = $stationId;
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function setEmployeeId($employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    public function setVehicleType($vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    public function getVehicleNumber()
    {
        return $this->vehicleNumber;
    }

    public function setVehicleNumber($vehicleNumber): void
    {
        $this->vehicleNumber = $vehicleNumber;
    }

    public function getDriverName()
    {
        return $this->driverName;
    }

    public function setDriverName($driverName): void
    {
        $this->driverName = $driverName;
    }

    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber($mobileNumber): void
    {
        $this->mobileNumber = $mobileNumber;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setServices($services): void
    {
        $this->services = $services;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time): void
    {
        $this->time = $time;
    }


    public function setBookingById(): bool
    {
        $query = "SELECT * FROM booking WHERE bookId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->bookId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rs) {
                $this->setCustomerId($rs['customerId']);
                $this->setStationId($rs['stationId']);
                $this->setEmployeeId($rs['employeeId']);
                $this->setVehicleType($rs['vehicleType']);
                $this->setVehicleNumber($rs['vehicleNumber']);
                $this->setDriverName($rs['driverName']);
                $this->setMobileNumber($rs['mobileNumber']);
                $this->setServices($rs['services']);
                $this->setStatus($rs['status']);
                $this->setRating($rs['rating']);
                $this->setDate($rs['date']);
                $this->setTime($rs['time']);
                return true;
            } else {
                echo "No Booking fo this booking id";
                return false;
            }
        } catch (Exception $ex) {
            echo "ERROR WHILE FIND BOOKING USING BOOKING ID <br>.$ex";
            return false;
        }
    }

    public function createAppointment(): bool
    {
        $query = "INSERT INTO booking"
            . "(customerId,stationId,employeeId,vehicleType,vehicleNumber,driverName,mobileNumber,services,status,date,time)"
            . "VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->customerId);
            $stmt->bindValue(2, $this->stationId);
            $stmt->bindValue(3, $this->employeeId);
            $stmt->bindValue(4, $this->vehicleType);
            $stmt->bindValue(5, $this->vehicleNumber);
            $stmt->bindValue(6, $this->driverName);
            $stmt->bindValue(7, $this->mobileNumber);
            $stmt->bindValue(8, $this->services);
            $stmt->bindValue(9, 0);
            $stmt->bindValue(10, $this->date);
            $stmt->bindValue(11, $this->time);
            $rs = $stmt->execute();

            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Error while create Service station' . $ex;
            return false;
        }
    }

    public function cancelBooking(): bool
    {
        $query = "UPDATE booking SET status = ? WHERE bookingId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, "cancel");
            $stmt->bindValue(2, $this->bookId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while cancel booking<br>.$e";
            return false;
        }
    }

    public function completedAppointmentForUser($userId): array
    {
        $query = "SELECT
                  b.bookId,
                  ss.name AS serviceStationName,
                  b.time,
                  b.vehicleNumber,
                  b.date,
                  u.firstName AS employeeName
                FROM
                  booking AS b
                INNER JOIN
                  servicestation AS ss ON b.stationId = ss.stationId
                INNER JOIN
                  employee AS e ON b.employeeId = e.employeeId
                INNER JOIN
                  user AS u ON e.userId = u.userId
                WHERE
                  b.status = 1
                  AND b.customerId = (SELECT customerId FROM customer WHERE userId = ?);
                ";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $userId);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            } else {
                return [];
            }
        } catch (PDOException $ex) {
            echo "Error While get all completed Services for users<br>.$ex";
            return [];
        }
    }

    public function nonCompletedAppointmentsForUser($userId)
    {

        $query = "SELECT
                  b.bookId,
                  ss.name AS serviceStationName,
                  b.time,
                  b.vehicleNumber,
                  b.date,
                  u.firstName AS employeeName
                FROM
                  booking AS b
                INNER JOIN
                  servicestation AS ss ON b.stationId = ss.stationId
                INNER JOIN
                  employee AS e ON b.employeeId = e.employeeId
                INNER JOIN
                  user AS u ON e.userId = u.userId
                WHERE
                  b.status = 0
                  AND b.customerId = (SELECT customerId FROM customer WHERE userId = ?);
                ";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $userId);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            } else {
                return [];
            }
        } catch (PDOException $ex) {
            echo "Error While get all non completed Services for users<br>.$ex";
            return [];
        }
    }

    public function findAvailableServiceStation($bookingData)
    {
        $name = $bookingData['name'];
        $vehicleType = $bookingData['vehicleType'];
        $vehicleNo = $bookingData['vehicleNo'];
        $district = $bookingData['district'];
        $date = $bookingData['date'];
        $services = $bookingData['services'];
        $time = $bookingData['time'];

        $query = "SELECT employeeId, stationId FROM employee WHERE employeeId NOT IN (
        SELECT employeeId FROM booking WHERE date = ? AND time = ? AND status = ?
    )";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $date);
            $stmt->bindValue(2, $time);
            $stmt->bindValue(3, 0);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $availableServiceStations = [];

            foreach ($rs as $row) {
                $employeeId = $row['employeeId'];
                $stationId = $row['stationId'];

                if ($this->canEmployee($employeeId, $services)) {
                    $stationDetails = $this->findServiceStation($stationId);

                    if (!empty($stationDetails)) {
                        $stationKey = $stationDetails['stationId'];

                        if (!array_key_exists($stationKey, $availableServiceStations)) {
                            $availableServiceStations[$stationKey] = [
                                'stationId' => $stationKey,
                                'availableEmployees' => [],
                                'stationDetails' => $stationDetails
                            ];
                        }
                        $availableServiceStations[$stationKey]['availableEmployees'][] = $employeeId;
                    }
                }
            }

            return array_values($availableServiceStations);
        } catch (PDOException $ex) {
            echo "Error while finding available service stations: $ex";
            return [];
        }
    }

    public function canEmployee($employeeId, $services)
    {
        $query = "SELECT services FROM employee WHERE employeeId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $employeeId);
            $stmt->execute();
            $rs = $stmt->fetchColumn();
            $employeeCan = true;

            $servicesDoByEmployee = json_decode($rs,true);
            foreach ($services as $ss){
                if (in_array($ss,$servicesDoByEmployee)){
                    continue;
                }else {
                    $employeeCan = false;
                }
            }
            return $employeeCan;
        } catch (PDOException $ex) {
            echo "Error while checking employee services: $ex";
            return false;
        }
    }




    public function findServiceStation($stationId){
        $query = "SELECT * FROM servicestation WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->execute([$stationId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error while finding service station: $ex";
            return [];
        }
    }

}
