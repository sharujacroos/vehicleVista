<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Admin.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $admin = new Admin();
    $stationCount = $admin->countStation();
    $customerCount = $admin->countCustomers();
    $bookingCount = $admin->countBooking();
    $stationRequest = $admin->viewAllServiceStationRequest();
    $response = array(
        'stationCount' => $stationCount,
        'customerCont' => $customerCount,
        'bookingCount' => $bookingCount,
        'stationRequest' => $stationRequest
    );
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $putData = json_decode(file_get_contents("php://input"), true);
    $stationId = $putData['id'];
    $membershipRenewalDate = $putData['membership'];
    if ($stationId) {
        $admin = new Admin();

        $result = $admin->approveServiceStation($stationId, $membershipRenewalDate);

        if ($result) {
            $response = array(
                'Status' => 'success',
                'Message' => 'Service station updated'
            );
        } else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Error updating service station'
            );
        }
    } else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'No ID provided for updating'
        );
    }
} else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Frontend not connected properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);