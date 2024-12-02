<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type:application/json; charset=UTF-8");

require_once __DIR__ . '/../classes/User.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if ($requestData) {
        $username = filter_var($requestData['username'], FILTER_VALIDATE_EMAIL);
        $password = trim($requestData['password']);
        $firstName = trim($requestData['firstName']);
        $lastName = trim($requestData['lastName']);
        $userRole = trim($requestData['userRole']);

        if (!$username || !empty($password) || !empty($firstName) || !empty($lastName) || !empty($userRole)) {
            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setUserRole($userRole);
            if ($user->isUsernameTaken()) {
                $response = array(
                    'Status' => 'failed',
                    'Message' => 'Username already taken'
                );
            } else {
                if ($user->signup()) {
                    $response = array(
                        'Status' => 'success',
                        'Message' => 'You have Successfully registered as ' . $username,
                        'userId' => $user->getUserId()
                    );
                } else {
                    $response = array(
                        'Status' => 'failed',
                        'Message' => "Registration failed. Try again"
                    );
                }
            }
        } else {
            $response = array(
                'Status' => 'failed',
                'Message' => 'Some fields are empty'
            );
        }
    } else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty data set received'
        );
    }
} else {
    $response = array(
        'Status' => 'error',
        'Message' => 'Frontend is not Connected correctly'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
