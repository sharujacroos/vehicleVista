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

        if (!$username || !empty($password)) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            if ($user->verifyUser()) {
                $user->setUserByUsername();
                $_SESSION['userId'] = $user->getUserId();
                $_SESSION['userRole'] = $user->getUserRole();
                $_SESSION['firstName'] = $user->getFirstName();
                $response = array(
                    'Status' => 'success',
                    'Message' => 'login Success',
                    'cookieSet' => true,
                    'userRole' => $user->getUserRole(),
                    'userId' => $user->getUserId(),
                    'firstName' => $user->getFirstName(),
                    'sessionStatus' => ''

            );
            } else {
                $response = array(
                    'Status' => 'failed',
                    'Message' => 'Login failed. Check your username and password'
                );
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
