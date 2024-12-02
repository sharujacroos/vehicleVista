<?php

require_once __DIR__ . '/../database/DbConnector.php';
require_once __DIR__ . '/Station.php';


class User
{

    private $userId;
    private $username;
    private $password;
    private $userRole;
    private $firstName;
    private $lastName;
    private $profilePic;

    private $db;

    public function getProfilePic()
    {
        return $this->profilePic;
    }

    public function setProfilePic($profilePic): void
    {
        $this->profilePic = $profilePic;
    }



    public function __construct()
    {
        $this->db = new DbConnector();
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUserRole()
    {
        return $this->userRole;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setUserRole($userRole): void
    {
        $this->userRole = $userRole;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setUserByUsername(): bool
    {
        $query = "SELECT * FROM user WHERE userName = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->username);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                $this->setFirstName($rs['firstName']);
                $this->setLastName($rs['lastName']);
                $this->setUserId($rs['userId']);
                $this->setUserRole($rs['userRole']);
                $this->setProfilePic($rs['profilePic']);
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while set user by userName<br>$ex";
            return false;
        }
    }

    public function setUserByUserId(): bool
    {
        $query = "SELECT * FROM user WHERE userId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->userId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs) {
                $this->setFirstName($rs['firstName']);
                $this->setLastName($rs['lastName']);
                $this->setUsername($rs['userName']);
                $this->setUserRole($rs['userRole']);
                $this->setProfilePic($rs['profilePic']);
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while set user by userId<br>$ex";
            return false;
        }
    }

    public function registerUser(): bool
    {
        $query = "INSERT INTO user (firstName, lastName, userName, password, userRole) VALUES (?, ?, ?, ?, ?)";
        $hashPassword = password_hash($this->password, PASSWORD_DEFAULT);
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->firstName);
            $stmt->bindValue(2, $this->lastName);
            $stmt->bindValue(3, $this->username);
            $stmt->bindValue(4, $hashPassword);
            $stmt->bindValue(5, $this->userRole);
            $stmt->execute();
            $user_id = $con->lastInsertId();
            $this->setUserId($user_id);
            if ($this->userId) {
                return $this->registerUserBaseRole();
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while user Registration<br>.$ex";
            return false;
        }
    }

    public function registerUserBaseRole(): bool
    {
        $table = $this->selectRole();
        if (!$table) {
            echo "Invalid user role.";
            return false;
        }
        $query = "INSERT INTO $table (userId) VALUES (?)";
        if ($table === 'employee'){
            return true;
        }else {
            try {
                $con = $this->db->getConnection();
                $stmt = $con->prepare($query);
                $stmt->bindValue(1, $this->userId);
                $rs = $stmt->execute();
                if ($rs) {
                    return $this->userId;
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                echo "Error while register user base role<br>$ex";
                return false;
            }
        }
    }

    public function selectRole(): false|string
    {
        if ($this->userRole === "user") {
            return "customer";
        } elseif ($this->userRole === "so") {
            return "owner";
        } elseif ($this->userRole === "se") {
            return "employee";
        } elseif ($this->userRole === "admin") {
            return "systemadministrator";
        }
        return false;
    }

    public function isUsernameTaken(): bool
    {
        $query = "SELECT * FROM user WHERE userName = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->username);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rs) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while is username taken <br>.$ex";
            return false;
        }
    }

    public function signup(): bool
    {
        if (!$this->isUsernameTaken()) {
            return $this->registerUser();
        } else {
            echo "Username already taken";
            return false;
        }
    }

    public function verifyUser(): bool
    {
        $query = "SELECT password FROM user WHERE userName = ?";

        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->username);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rs && password_verify($this->password, $rs['password'])) {
                $this->setUserByUsername();
                $this->setupCookie();
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Error While login<br>' . $ex;
            return false;
        }
    }

    public function setupCookie(): void
    {
        $token = rand(100000, 999999);
        $cookie_name = "LoggedIn";
        $cookie_value = "userId=" . $this->userId . "&token=" . $token;
        $expiration = time() + 3600 * 24 * 30;
        setcookie($cookie_name, $cookie_value, $expiration);
    }

    public function updateProfile(): bool
    {
        $query = "UPDATE user SET firstName = ?, lastName = ? WHERE userId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $this->firstName);
            $stmt->bindValue(2, $this->lastName);
            $stmt->bindValue(3, $this->userId);
            $rs = $stmt->execute();
            if ($rs) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo "Error while update profile<br>$ex";
            return false;
        }
    }

    public function completedServicesForCustomer($customerId): array
    {
        $query = "SELECT * FROM booking WHERE customerId = ? AND status = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,$customerId);
            $stmt->bindValue(2,1);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs){
                return $rs;
            }else {
                return [];
            }

        }catch (PDOException $ex){
            echo "Error while get all completed bookings for user<br>.$ex";
            return [];
        }
    }

    public function nonCompletedServicesForCustomer($customerId): array
    {
        $query = "SELECT * FROM booking WHERE customerId = ? AND status = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1,$customerId);
            $stmt->bindValue(2,0);
            $stmt->execute();
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rs){
                return $rs;
            }else {
                return [];
            }

        }catch (PDOException $ex){
            echo "Error while get all not completed bookings for user<br>.$ex";
            return [];
        }
    }

    public function findCutomerIdByUserId($userId){
        $query = "SELECT customerId FROM customer WHERE userId = ?";
        try {
            $con = $this->db->getConnection();
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $userId);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rs){
                return$rs['customerId'];
            }else {
                return 0;
            }
        }catch (PDOException $ex){
            echo "Error while find customer id using userId<br>.$ex";
            return 0;
        }
    }
}


