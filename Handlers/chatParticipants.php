<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../classes/Chat.php";
require_once __DIR__ . "/../classes/User.php";  // Assuming User class is in a different file
$chat = new Chat();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData) {
        $userId = $requestData['userId'];
        $chatParticipants = $chat->getChatParticipants($userId);
        $chatParticipantsDetails = [];

        foreach ($chatParticipants as $p){
            $receiverId = $p;
            $chatHistory = $chat->viewChatHistory($userId, $receiverId);
            $lastMessage = end($chatHistory);
            $lastMessageText = $lastMessage['message'];

            $user->setUserId($receiverId);
            $user->setUserByUserId();
            $firstName = $user->getFirstName();
            $profilePic = $user->getProfilePic();
            $userRole = $user->getUserRole();

            $temp = [
                'firstName' => $firstName,
                'profilePic' => $profilePic,
                'lastMessage' => $lastMessageText,
                'userRole' => $userRole
            ];

            $chatParticipantsDetails[] = $temp;

        }

        $response = array(
            'Status' => 'success',
            'chatParticipants' => $chatParticipantsDetails
        );
    } else {
        $response = array(
            'Status' => 'failed',
            'Message' => 'Empty array received'
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
