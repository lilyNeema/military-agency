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

// Query to fetch officers on leave
$sql = "SELECT officer_id, officer_name, officer_rank, officer_category, leave_start_date, leave_end_date FROM officers_on_leave";
$result = $conn->query($sql);

// Prepare an array to hold the fetched data
$data = array();

if ($result->num_rows > 0) {
    // Fetch data row by row
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
