<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Station.php";
require_once __DIR__ . "/../classes/Admin.php";


$station = new Station();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData){
        $stationId = $requestData['stationId'];
        $station->setStationId($stationId);
        $employees = $station->getAllEmployeesForStation();

        $response = array(
            'Status' => 'success',
            'employees' => $employees
        );

    }else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty array received'
        );
    }
}elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    $employeeId = $requestData['id'];
    $admin = new Admin();
    if ($employeeId) {
        if ($admin->deleteUser($employeeId)) {
            $response = array(
                'Status' => 'success',
                'Message' => 'Employee deleted'
            );
        } else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Error deleting service station'
            );
        }
    } else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'No ID provided for deletion'
        );
    }
} else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'frontend doesn\'t connect properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);


