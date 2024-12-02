<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Admin.php";
require_once __DIR__ . "/../classes/Station.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $admin = new Admin();
    $station = new Station();

    $allStations = $admin->viewAllServiceStations();
    $serviceStations = array();

    foreach ($allStations as $row){
        $station->setStationId($row['stationId']);
        $completedServices = count($station->completedAppointmentForOwner());
        $temp = [
            'stationId' => $row['stationId'],
            'name' => $row['name'],
            'rating' => '',
            'subscriptionDate' => $row['membershipRenewalDate'],
            'completedServices' => $completedServices,
            'location' => $row['location'],
            'images' => $row['images'],
            'district' => $row['district']
        ];
        $serviceStations[] = $temp;
    }
    $response = array(
        'Status' => 'success',
        'serviceStations' => $serviceStations
    );
}
else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Frontend not connected properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);


