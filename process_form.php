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
    $officerId = $_POST['officerId'];
    $officerName = $_POST['officerName'];
    $officerRank = $_POST['officerRank'];
    $officerCategory = $_POST['officerCategory'];

    $sql = "INSERT INTO officers (officer_id, name, rank, category) 
            VALUES ('$officerId',  '$officerName', '$officerRank', '$officerCategory')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New officer added successfully');</script>";
    } else {
        echo "<script>alert('Error adding officer: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>
