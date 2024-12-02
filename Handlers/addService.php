<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Services.php";

$services = new Services();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData){
        $userId = $requestData['userId'];
        $service = $requestData['serviceName'];
        $vehicleType = $requestData['vehicleType'];
        $price = $requestData['price'];
        $duration = $requestData['timeDuration'];

        $time = null;

        if ($duration >= 60){
            $hours = $duration / 60;
            $min = $duration % 60;

            $time = "$hours:$min:00";
        }else {
            $time = "00:$duration:00";
        }

        $stationID = $services->findStationByUserID($userId);

        $services->setServiceName($service);
        $services->setVehicleType($vehicleType);
        $services->setServiceCharge($price);
        $services->setDuration($time);
        $services->setStationId($stationID);

        if ($services->addService()){
            $response = array(
                'Status' => 'success',
                'Message' => 'Service Added succesfully'
            );
        }else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Service added fail try again later'
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
        'Message' => 'frontend doesn\'t connect properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);


