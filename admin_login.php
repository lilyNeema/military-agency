<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "military_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $officerId = $_POST['officerId'];
    $password = $_POST['password'];

    $sql = "SELECT officer_id FROM admin WHERE officer_id = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $officerId, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Admin found, allow login
        $_SESSION['officerId'] = $officerId;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Admin not found or incorrect credentials
        echo "<script>alert('Officer ID or password incorrect');</script>";
        echo "<script>window.location.href = 'index.html';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
