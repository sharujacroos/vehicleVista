<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type:application/json; charset=UTF-8");

require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Station.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    if ($requestData) {
        $firstName = $requestData['firstName'];
        $stationId = $requestData['stationId'];
        $date = $requestData['date'];
        $username = $requestData['username'];
        $password = $requestData['password'];
        $userRole = $requestData['userRole'];

        $station = new Station();
        $user = new User();

        $station->setStationId($stationId);

        $user->setFirstName($firstName);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setUserRole($userRole);

        if ($user->signup()){
            if ($station->createEmployee($user->getUserId())){
                $response = array(
                    'Status' => 'success',
                    'Message' => 'Employee created Successfully',
                    'employeeId' => $user->getUserId()
                );
            }else{
                $response = array(
                    'Status' => 'failed',
                    'Message' => 'Employee created failed. try again later'
                );
            }
        }else{
            $response = array(
                'Status' => 'failed',
                'Message' => 'Error While create Employee'
            );
        }
    } else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty Array received'
        );
    }
} else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Frontend is not connected properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
