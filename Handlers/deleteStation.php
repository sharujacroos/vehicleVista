<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Admin.php";

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $deleteData = json_decode(file_get_contents("php://input"), true);
    $stationId = $deleteData['id'];
    if ($stationId) {
        $admin = new Admin();
        $result = $admin->deleteServiceStation($stationId);

        if ($result) {
            $response = array(
                'Status' => 'success',
                'Message' => 'Service station deleted'
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
        'Message' => 'Frontend not connected properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);