<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Booking.php";
$booking = new Booking();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);

    if ($requestData){
        $available = $booking->findAvailableServiceStation($requestData);
        $response = array(
            'Status' => 'Success',
            'available' => $available
        );
    }else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty data received'
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