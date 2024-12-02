<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Booking.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"),true);
    if ($requestData){
        $userId = $requestData['userId'];
        $booking = new Booking();

        $completedServices = $booking->completedAppointmentForUser($userId);
        $nonCompletedService = $booking->nonCompletedAppointmentsForUser($userId);

        $response = array(
            'Status' => 'success',
            'completedServices' => $completedServices,
            'nonCompletedServices' => $nonCompletedService
        );
    }else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty Data Received',
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