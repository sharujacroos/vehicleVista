<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once "classes/User.php";
require_once "classes/Station.php";
require_once "classes/Booking.php";
require_once "classes/Services.php";
require_once "classes/Chat.php";
require_once "database/DbConnector.php";
$db = new DbConnector();
$booking = new Booking();
$service = new Services();
$station = new Station();
$chat = new Chat();
$user = new User();

$con = $db->getConnection();
// Function to find available service stations
//function findAvailableServiceStations($bookingData)
//{
//    global $con;
//    $name = $bookingData['name'];
//    $vehicleType = $bookingData['vehicleType'];
//    $vehicleNo = $bookingData['vehicleNo'];
//    $district = $bookingData['district'];
//    $date = $bookingData['date'];
//    $services = $bookingData['services'];
//    $time = $bookingData['time'];
//
//    // Connect to your database
//
//    // Find employees without appointments at the specified time
//    $query = "SELECT employeeId, stationId FROM employee WHERE employeeId NOT IN (
//        SELECT employeeId FROM booking WHERE date = :bookingDate AND time = :bookingTime
//    )";
//    $stmt = $con->prepare($query);
//    $stmt->execute(array(':bookingDate' => $date, ':bookingTime' => $time));
//
//    $availableServiceStations = array();
//
//    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        $employeeId = $row['employeeId'];
//        $stationId = $row['stationId'];
//
//        // Check if the employee offers the required services
//        $employeeServicesQuery = "SELECT services FROM employee WHERE employeeId = :employeeId";
//        $stmt = $con->prepare($employeeServicesQuery);
//        $stmt->execute(array(':employeeId' => $employeeId));
//        $employeeServices = $stmt->fetchColumn();
//
//        // Compare booking services with employee services
//        $canPerformServices = array_intersect($services, explode(',', $employeeServices));
//
//        if (!empty($canPerformServices)) {
//            // Get service station details
//            $stationDetailsQuery = "SELECT * FROM servicestation WHERE stationId = :stationId";
//            $stmt = $con->prepare($stationDetailsQuery);
//            $stmt->execute(array(':stationId' => $stationId));
//            $stationDetails = $stmt->fetch(PDO::FETCH_ASSOC);
//
//            // Add available service station details to the result array
//            $availableServiceStations[] = $stationDetails;
//        }
//    }
//
//    return $availableServiceStations;
//}

// Usage
//$bookingData = [
//    'name' => 'kishobigan',
//    'vehicleType' => 'lorry',
//    'vehicleNo' => 'LJ2974',
//    'district' => 'mullaithivu',
//    'date' => '2023-11-08',
//    'services' => ['oil change', 'body wash'],
//    'time' => '08:00:00'
//];
//
//$bookingData1 = [
//    'name' => "kishobigan",
//    'vehicleType' => "lorry",
//    'vehicleNo' => "LJ2974",
//    'district' => "Mullaitivu",
//    'date' => "2023-11-08",  // Ensure the date follows the format YYYY-MM-DD
//    'services' => ['body wash', 'oil change'],
//    'time' => '08:00:00'
//];
//
//
////$availableStations = findAvailableServiceStations($bookingData);
////print_r($availableStations); // Display available service stations
//
//$available = $booking->findAvailableServiceStation($bookingData);
//
//foreach ($available as $stationId => $stationData) {
//    echo "Station ID: $stationId" . PHP_EOL;
//    // Display station details
//    $stationDetails = $stationData['details'];
//    echo "Name: " . $stationDetails['name'] . PHP_EOL;
//    echo "Location: " . $stationDetails['location'] . PHP_EOL;
//    // Display available employees
//    $availableEmployees = $stationData['availableEmployees'];
//    echo "Available Employees: " . implode(', ', $availableEmployees) . PHP_EOL;
//    echo "---------------------------------" . PHP_EOL;
//}

//echo $service->findEmployeeByUserID(176);
//

$userId = 143;
$chatParticipants = $chat->getChatParticipants($userId);
$chatParticipantsDetails = [];

foreach ($chatParticipants as $p){
    $receiverId = $p;
    $chatHistory = $chat->viewChatHistory($userId, $receiverId);
    $lastMessage = end($chatHistory);
    $lastMessageText = $lastMessage['message'];

    $user->setUserId($userId);
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

    $chatParticipants = $temp;

}



echo '<h2>Chat Participants</h2>';
var_dump($chatParticipants);
echo "<br>";
print_r($chatParticipants);
echo '<h2>Chat Participant</h2>';
echo '<ul>';

foreach ($chatParticipants as $key => $value) {
    echo '<li><strong>' . ucfirst($key) . ':</strong> ' . $value . '</li>';
}

echo '</ul>';
echo "<hr><br>";

