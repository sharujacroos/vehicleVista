<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Admin.php";
require_once __DIR__ . "/../classes/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $admin = new Admin();
    $user = new User();

    $allUsers = $admin->viewAllUsers();
    $users = array();

    foreach ($allUsers as $row) {
        $completedServices = $user->completedServicesForCustomer($row['customerId']);
        $countOfCompletedServices = count($completedServices);
        $temp = [
            'userId' => $row['userId'],
            'name' => $row['firstName'],
            'emailId' => $row['userName'],
            'completedServices' => $countOfCompletedServices
        ];
        $users[] = $temp;
    }
    $response = array(
        'Status' => 'success',
        'users' => $users
    );
} else {
    $response = array(
        'Status' => 'failed',
        'Message' => 'Frontend not connected properly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);


