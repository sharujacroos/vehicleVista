<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type:application/json; charset=UTF-8");

require_once __DIR__.'/../classes/Station.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $requestData = json_decode(file_get_contents('php://input'), true);

    if ($requestData){
        $ownerId = $requestData['ownerId'];
        $name = $requestData['name'];
        $location = $requestData['address'];
        $district = $requestData['district'];
        $licenseNumber = $requestData['licence'];
        $employeeCount = $requestData['employee'];
        $features = json_encode($requestData['features']);
        $images = json_encode($requestData['images']);
        $openingTime = $requestData['O_time'];
        $closingTime = $requestData['C_time'];

        $station = new Station();

        $station->setOwnerId($ownerId);
        $station->setName($name);
        $station->setLocation($location);
        $station->setDistrict($district);
        $station->setLicenceNumber($licenseNumber);
        $station->setEmployeeCount($employeeCount);
        $station->setFeatures($features);
        $station->setImages($images);
        $station->setOpeningTime($openingTime);
        $station->setClosingTime($closingTime);

        if ($station->createServiceStation()){
            $response = array(
                'Status' => 'success',
                'Message' => 'Successfully Service Station created'
            );
        }else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Service station Creation Failed. Please try again later'
            );
        }

    }else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty Data set received'
        );
    }
}else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Frontend Doe\'snot Connected'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
