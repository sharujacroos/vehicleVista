<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type:application/json; charset=UTF-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__."/../classes/Station.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $requestData = json_decode(file_get_contents('php://input'), true);
    $userId = $requestData['userId'];
    $station = new Station();
    $station->setOwnerId($userId);
    if ($station->setStationByOwnerId()){
        $employeeCount = $station->countEmployeesByStationId();
        $response = array(
            'openingTime' => $station->getOpeningTime(),
            'closingTime' => $station->getClosingTime(),
            'employeeCount' => $employeeCount,
            'features' => json_decode($station->getFeatures()),
            'images' => json_decode($station->getImages()),
            'name' => $station->getName(),
            'address' => $station->getLocation(),
            'licenceNumber' => $station->getLicenceNumber(),
            'services' => $station->getAllServiceForStation(),
            'stationId' => $station->getStationId(),

        );
    }else{
        $response = array(
            'Status' => 'failed',
            'Message' => 'Failed while set owner Id'
        );
    }
}else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Front end is not connected Correctly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);

