<?php
ob_start(); // Start output buffering

// Ensure to include the FPDF library
require('fpdf.php');

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
    $reportType = $_POST['reportType'];
    $sql = "";

    switch ($reportType) {
        case 'all_officers':
            $sql = "SELECT officer_id, name, rank, category FROM officers";
            break;
        case 'officers_on_leave':
            $sql = "SELECT officer_id, full_name, leave_start, leave_end FROM officers_on_leave";
            break;
        case 'officers_on_duty':
            $sql = "SELECT officer_id, name, rank, category FROM officers";
            break; // Missing break statement was causing syntax error
        default:
            echo "Invalid report type";
            exit;
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Initialize PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Table header
        switch ($reportType) {
            case 'all_officers':
            case 'officers_on_duty':
                $pdf->Cell(40, 10, 'Officer ID', 1);
                $pdf->Cell(60, 10, 'Officer Name', 1);
                $pdf->Cell(40, 10, 'Officer Rank', 1);
                $pdf->Cell(50, 10, 'Officer Category', 1);
                $pdf->Ln();
                break;
            case 'officers_on_leave':
                $pdf->Cell(40, 10, 'Officer ID', 1);
                $pdf->Cell(60, 10, 'Full Name', 1);
                $pdf->Cell(60, 10, 'Leave Start Date', 1);
                $pdf->Cell(60, 10, 'Leave End Date', 1);
                $pdf->Ln();
                break;
            default:
                echo "Invalid report type";
                exit;
        }

        // Table content
        while ($row = $result->fetch_assoc()) {
            switch ($reportType) {
                case 'all_officers':
                case 'officers_on_duty':
                    $pdf->Cell(40, 10, $row['officer_id'], 1);
                    $pdf->Cell(60, 10, $row['name'], 1);
                    $pdf->Cell(40, 10, $row['rank'], 1);
                    $pdf->Cell(50, 10, $row['category'], 1);
                    $pdf->Ln();
                    break;
                case 'officers_on_leave':
                    $pdf->Cell(40, 10, $row['officer_id'], 1);
                    $pdf->Cell(60, 10, $row['full_name'], 1);
                    $pdf->Cell(60, 10, $row['leave_start'], 1);
                    $pdf->Cell(60, 10, $row['leave_end'], 1);
                    $pdf->Ln();
                    break;
            }
        }

        // Output PDF as download
        $pdf->Output('D', 'report.pdf');
        exit;
    } else {
        echo "No records found";
    }
}

$conn->close();

ob_end_clean(); // Clean the output buffer before sending PDF
?>
