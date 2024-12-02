<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Booking.php";
require_once __DIR__ . "/../classes/User.php";
$user = new User();
$booking = new Booking();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData){
        $services = json_encode($requestData['services']);




        $customerId = $user->findCutomerIdByUserId($requestData['customerId']);
        $booking->setCustomerId($customerId);
        $booking->setStationId($requestData['stationId']);
        $booking->setEmployeeId($requestData['employeeId']);
        $booking->setVehicleType($requestData['vehicleType']);
        $booking->setVehicleNumber($requestData['vehicleNumber']);
        $booking->setDriverName($requestData['driverName']);
        $booking->setMobileNumber($requestData['mobileNumber']);
        $booking->setServices(($services));
        $date = strtotime($requestData['date']);
        $booking->setDate(date('Y-m-d', $date));
        $booking->setTime($requestData['time']);

        if ($booking->createAppointment()){
            $response = array(
                'Status' => 'success',
                'Message' => 'Booking Created Successfully',
            );
        }else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Error while create appointment'
            );
        }

    }else {
        $response = array(
            'Status' => 'Failed',
            'Message' => 'Empty Data received'
        );
    }
} else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'ForntEND doesnot connect properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);


