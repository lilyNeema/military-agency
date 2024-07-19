<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "military_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $officerId = $_POST['officerId'];
    $leaveStart = $_POST['leaveStart'];
    $leaveEnd = $_POST['leaveEnd'];

    $sql = "INSERT INTO officers_on_leave (full_name, officer_id, leave_start, leave_end) 
            VALUES ('$fullName', '$officerId', '$leaveStart', '$leaveEnd')";

    if ($conn->query($sql) === TRUE) {
        echo "Leave application submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
