<?php
require_once("vendor/autoload.php");
require_once("../functions.php");

define('AT_USERNAME', 'sandbox');
define('AT_APIKEY', '021274f69e8cab8353a2f220d5bdcb0a184b1b97134deebed54371c3a8a8c26a');
define('AT_ENVIRONMENT', 'sandbox'); // or 'production' based on your Africa's Talking environment

$sessionId = $_POST['sessionId'];
$serviceCode = $_POST['serviceCode'];
$phoneNumber = $_POST['phoneNumber'];
$text = $text = trim($_POST['text']); // Trim whitespace

// Check if the user is registered
$check = ussdlogin($phoneNumber);

if ($check > 0) {
    // User is registered
    $userInput = explode('*', $text);

    // Check the user's input stage
    $inputStage = count($userInput);

    switch ($inputStage) {
        case 1:
            // Initial stage, ask for password
            echo "CON Enter password:";
            break;

        case 2:
            // Password provided, check login
            $password = $userInput[1];
            $login = ussdLoginFinal($password, $phoneNumber);

            if ($login == "yes") {
                // Successful login. Provide main menu options.
                echo "CON Successful login.\n1. Student Attendance\n2. Student Results\n2. Student Fees Statement";
            } else {
                // Handle unsuccessful login
                echo "END Invalid credentials. Login failed.";
            }
            break;

        case 3:
            // User selected an option from the main menu
            $selectedOption = $userInput[2];

            switch ($selectedOption) {
                case 1:
                    // Logic for Student Attendance option
                    $conn = connectToDatabase();

                    // Implement logic to fetch student data based on the parent's user ID
                     $sql = "SELECT * FROM users WHERE phone_number=".$phoneNumber;
                
                    $result = $conn->query($sql);
                    $row=mysqli_fetch_assoc($result);// Replace with actual logic to fetch attendance data
                    echo "END Student Attendance:\n.".$row['phone_number'];
                    break;

                case 2:
                    // Logic for Student Results option
                    $resultsData =getStudentDataForParent($phoneNumber)[2];// Replace with actual logic to fetch results data
                    echo "END Student Results:\n$resultsData";
                    break;

                default:
                    // Invalid option selected
                    echo "END Invalid option selected.";
                    break;
            }
            break;

        default:
            // Invalid input stage
            echo "END Invalid input.";
            break;
    }
} else {
    // User not registered in the system
    echo "END User not registered in the system ";
}

// Replace the following functions with actual logic to fetch attendance and results data
function retrieveStudentAttendance() {
    // Implement logic to retrieve and format attendance data
    return "Attendance Data: ..."; // Replace with actual data
}

function retrieveStudentResults() {
    // Implement logic to retrieve and format results data
    $res=getStudentDataForParent($phoneNumber);
   
    return "Results Data:$res"; // Replace with actual data
}
?>
