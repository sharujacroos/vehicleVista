<?php

require_once __DIR__ . '/../database/DbConnector.php';


class Station
{
    private $stationId;
    private $ownerId;
    private $name;
    private $location;
    private $licenceNumber;
    private $district;
    private $employeeCount;
    private $features;
    private $images;
    private $membershipRenewalDate;
    private $openingTime;
    private $closingTime;
    private $Status;

    private $db;

    public function __construct()
    {
        $this->db = new DbConnector();
    }


    public function getStationId()
    {
        return $this->stationId;
    }


    public function setStationId($stationId): void
    {
        $this->stationId = $stationId;
    }


    public function getOwnerId()
    {
        return $this->ownerId;
    }


    public function setOwnerId($ownerId): void
    {
        $this->ownerId = $ownerId;
    }


    public function getName()
    {
        return $this->name;
    }


    public function setName($name): void
    {
        $this->name = $name;
    }


    public function getLocation()
    {
        return $this->location;
    }


    public function setLocation($location): void
    {
        $this->location = $location;
    }


    public function getLicenceNumber()
    {
        return $this->licenceNumber;
    }


    public function setLicenceNumber($licenceNumber): void
    {
        $this->licenceNumber = $licenceNumber;
    }


    public function getDistrict()
    {
        return $this->district;
    }


    public function setDistrict($district): void
    {
        $this->district = $district;
    }


    public function getEmployeeCount()
    {
        return $this->employeeCount;
    }


    public function setEmployeeCount($employeeCount): void
    {
        $this->employeeCount = $employeeCount;
    }


    public function getFeatures()
    {
        return $this->features;
    }


    public function setFeatures($features): void
    {
        $this->features = $features;
    }


    public function getImages()
    {
        return $this->images;
    }


    public function setImages($images): void
    {
        $this->images = $images;
    }


    public function getMembershipRenewalDate()
    {
        return $this->membershipRenewalDate;
    }


    public function setMembershipRenewalDate($membershipRenewalDate): void
    {
        $this->membershipRenewalDate = $membershipRenewalDate;
    }


    public function getOpeningTime()
    {
        return $this->openingTime;
    }


    public function setOpeningTime($openingTime): void
    {
        $this->openingTime = $openingTime;
    }


    public function getClosingTime()
    {
        return $this->closingTime;
    }


    public function setClosingTime($closingTime): void
    {
        $this->closingTime = $closingTime;
    }


    public function getStatus()
    {
        return $this->Status;
    }


    public function setStatus($Status): void
    {
        $this->Status = $Status;
    }

    public function setStationByOwnerId(): bool
    {
        $query = "SELECT * FROM servicestation WHERE ownerId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->ownerId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                $this->setStationId($rs['stationId']);
                $this->setName($rs['name']);
                $this->setLocation($rs['location']);
                $this->setLicenceNumber($rs['licenceNumber']);
                $this->setDistrict($rs['district']);
                $this->setEmployeeCount($rs['employeeCount']);
                $this->setFeatures($rs['features']);
                $this->setImages($rs['images']);
                $this->setMembershipRenewalDate($rs['membershipRenewalDate']);
                $this->setOpeningTime($rs['openingTime']);
                $this->setClosingTime($rs['clossingTime']);
                $this->setStatus($rs['status']);
                return true;
            } else {
                echo 'Empty Data';
                return false;
            }
        } catch (PDOException $e) {
            echo "Error while set service station by owner<br>.$e";
            return false;
        }
    }


    public function setStationById()
    {
        $query = "SELECT * FROM servicestation WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                $this->setOwnerId($rs['ownerId']);
                $this->setName($rs['name']);
                $this->setLocation($rs['location']);
                $this->setLicenceNumber($rs['licenceNumber']);
                $this->setDistrict($rs['district']);
                $this->setEmployeeCount($rs['employeeCount']);
                $this->setFeatures($rs['features']);
                $this->setImages($rs['images']);
                $this->setMembershipRenewalDate($rs['membershipRenewalDate']);
                $this->setOpeningTime($rs['openingTime']);
                $this->setClosingTime($rs['closingTime']);
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while set station by stationId<br>.$ex";
            return false;
        }
    }

    public function createServiceStation(): bool
    {
        $query = "INSERT INTO "
            . "servicestation (ownerId,name,location,licenceNumber,district,features,images,openingTime,clossingTime,status) "
            . "VALUES (?,?,?,?,?,?,?,?,?,?)";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->ownerId);
            $stmt->bindValue(2, $this->name);
            $stmt->bindValue(3, $this->location);
            $stmt->bindValue(4, $this->licenceNumber);
            $stmt->bindValue(5, $this->district);
            $stmt->bindValue(6, $this->features);
            $stmt->bindValue(7, $this->images);
            $stmt->bindValue(8, $this->openingTime);
            $stmt->bindValue(9, $this->closingTime);
            $stmt->bindValue(10, 0);
            $rs = $stmt->execute();

            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Error while create service station' . $ex;
            return false;
        }
    }

    public function updateServiceStation(): bool
    {
        $query = "UPDATE servicestation "
            . "SET features = ?, images = ?, openingTime = ?, closingTime = ?"
            . "WHERE stationId = ?";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->features);
            $stmt->bindValue(2, $this->images);
            $stmt->bindValue(3, $this->openingTime);
            $stmt->bindValue(4, $this->closingTime);
            $stmt->bindValue(5, $this->stationId);
            $rs = $stmt->execute();

            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Error while update service station' . $ex;
            return false;
        }
    }

    public function createEmployee($userId): bool
    {
        $query = "INSERT INTO employee (stationId,userId)"
            . "VALUES (?,?)";

        try {
            $db = new DbConnector();
            $con = $db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->bindValue(2, $userId);
            $rs = $stmt->execute();

            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Error While Create Employee' . $ex;
            return false;
        }
    }

    public function deleteEmployee($employeeId): bool
    {
        $query = "DELETE FROM employee WHERE employeeId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $employeeId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while Delete employee<br>.$ex";
            return false;
        }
    }

    public function completedAppointmentForOwner(): false|array
    {
        $query = "SELECT 
                e.employeeId,
                u.firstName AS employeeName,
                b.date,
                b.vehicleType,
                b.driverName,
                b.vehicleNumber,
                b.services,
                b.time,
                c.customerId,
                cu.profilePic AS customerProfilePic
                FROM booking b
                INNER JOIN employee e ON b.employeeId = e.employeeId
                INNER JOIN user u ON e.userId = u.userId
                INNER JOIN customer c ON b.customerId = c.customerId
                INNER JOIN user cu ON c.userId = cu.userId 
                WHERE b.stationId = ? AND b.status=?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->bindValue(2, 1);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rs) {
                $appointmentsData = array();
                foreach ($rs as $row) {
                    $appointment = array(
                        'employeeId' => $row['employeeId'],
                        'employeeName' => $row['employeeName'],
                        'date' => $row['date'],
                        'vehicleType' => $row['vehicleType'],
                        'driverName' => $row['driverName'],
                        'vehicleNo' => $row['vehicleNumber'],
                        'services' => $row['services'],
                        'customerId' => $row['customerId'],
                        'customerProfilePic' => $row['customerProfilePic'],
                        'time' => $row['time']
                        // Add other appointment data as needed
                    );
                    $appointmentsData[] = $appointment;
                }
                return $appointmentsData;
            } else {
                return [];
            }
        } catch (Exception $ex) {
            echo "Error while retrieving appointments for Owner: $ex";
            return [];
        }
    }


    public function NonCompletedAppointmentForOwner(): false|array
    {
        $query = "SELECT 
                e.employeeId,
                u.firstName AS employeeName,
                b.date,
                b.vehicleType,
                b.driverName,
                b.time,
                b.vehicleNumber,
                b.services,
                c.customerId,
                cu.profilePic AS customerProfilePic
                FROM booking b
                INNER JOIN employee e ON b.employeeId = e.employeeId
                INNER JOIN user u ON e.userId = u.userId
                INNER JOIN customer c ON b.customerId = c.customerId
                INNER JOIN user cu ON c.userId = cu.userId 
                WHERE b.stationId = ? AND b.status=?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->bindValue(2, 0);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rs) {
                $appointmentsData = array();
                foreach ($rs as $row) {
                    $appointment = array(
                        'employeeId' => $row['employeeId'],
                        'employeeName' => $row['employeeName'],
                        'date' => $row['date'],
                        'vehicleType' => $row['vehicleType'],
                        'driverName' => $row['driverName'],
                        'vehicleNo' => $row['vehicleNumber'],
                        'services' => $row['services'],
                        'customerId' => $row['customerId'],
                        'customerProfilePic' => $row['customerProfilePic'],
                        'time' => $row['time']
                        // Add other appointment data as needed
                    );
                    $appointmentsData[] = $appointment;
                }
                return $appointmentsData;
            } else {
                return [];
            }
        } catch (Exception $ex) {
            echo "Error while retrieving appointments for Owner: $ex";
            return [];
        }
    }

    public function countEmployeesByStationId(): int
    {
        $query = "SELECT COUNT(*) AS employeeCount FROM employee WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['employeeCount'])) {
                return (int)$result['employeeCount'];
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo "Error while counting employees by stationId: $ex";
            return 0;
        }
    }

    public function getAllServiceForStation(): array
    {
        $query = "SELECT * FROM services WHERE stationId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->stationId);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rs) {
                $services = array();
                foreach ($rs as $service) {
                    $vehicleType = $service['vehicleType'];
                    if (!array_key_exists($vehicleType, $services)) {
                        $services[$vehicleType] = array('vehicleType' => $vehicleType, 'services' => []);
                    }
                    $services[$vehicleType]['services'][] = array(
                        "serviceName" => $service['serviceName'],
                        "serviceCharge" => $service['serviceCharge'],
                        "duration" => $service['duration']
                    );
                }
                return array_values($services);
            } else {
                return [];
            }
        } catch (PDOException $ex) {
            echo 'Error While getting all service station for a particular service station<br>' . $ex;
            return [];
        }
    }

    public function getAllEmployeesForStation(): array
    {
        $query = "SELECT u.*, e.*
                    FROM employee e
                    JOIN user u ON e.userId = u.userId
                    WHERE e.stationId = ?;
                    ";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, 43);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs) {
                return $rs;
            }else {
                echo 'no data';
                return [];
            }
        }catch (PDOException $ex){
            echo "Error while get all employees for station<br>$ex";
            return [];
        }
    }

}
