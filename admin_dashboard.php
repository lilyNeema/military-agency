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

// Query to fetch officers on duty
$sql_duty = "SELECT officer_id, name, rank, category FROM officers";
$result_duty = $conn->query($sql_duty);

// Query to fetch officers on leave
$sql_leave = "SELECT officer_id, full_name, leave_start, leave_end FROM officers_on_leave";
$result_leave = $conn->query($sql_leave);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Dashboard</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/icon.png" rel="icon">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .topnav {
            background-color: #004d00;
            overflow: hidden;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .topnav a:hover {
            background-color: #003300;
            color: #004d00;
        }

        .topnav a.active {
            background-color: #003300;
            color: white;
        }

        .container {
            display: flex;
            flex: 1;
            margin-top: 50px; /* Adjust this value if the height of the topnav changes */
        }

        .sidebar {
            background-color: #004d00;
            color: white;
            width: 250px;
            height: calc(100vh - 50px); /* Adjust this value if the height of the topnav changes */
            padding: 20px;
            box-sizing: border-box;
            position: fixed;
            top: 50px; /* Adjust this value if the height of the topnav changes */
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .sidebar a.active, .sidebar a:hover {
            background-color: #003300;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-left: 2px solid #004d00;
            box-sizing: border-box;
        }

        h2 {
            color: #004d00;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #004d00;
            outline: none;
        }

        .submit-btn {
            background-color: #004d00;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #003300;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<nav class="topnav">
         <a class="active " href="index.html">Home</a>
         <a href="leave.html">Apply for Leave</a>
         <a href="services.html">About Us</a>
   </nav>
    <div class="container">
        <div class="sidebar">
            <a href="#new-officer" class="active">New Officer</a>
            <a href="#officers-on-duty">Officers on Duty</a>
            <a href="#officers-on-leave">Officers on Leave</a>
            <a href="#reports">Reports</a>
        </div>

        <div class="main-content">
            <div id="new-officer" class="content">
                <h2>New Officer Recording Form</h2>
                <form id="officerForm" method="POST" action="process_form.php">
                    <div class="form-group">
                        <label for="officerId">Officer ID (Auto-generated)</label>
                        <input type="text" id="officerId" name="officerId" readonly>
                    </div>
                    <div class="form-group">
                        <label for="officerName">Officer Name</label>
                        <input type="text" id="officerName" name="officerName" required>
                    </div>
                    <div class="form-group">
                        <label for="officerRank">Officer Rank</label>
                        <select id="officerRank" name="officerRank" required>
                            <option value="">Select Rank</option>
                            <option value="Private">Private</option>
                            <option value="Lieutenant">Lieutenant</option>
                            <!-- Add more ranks as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="officerCategory">Officer Category</label>
                        <select id="officerCategory" name="officerCategory" required>
                            <option value="">Select Category</option>
                            <option value="Army">Army</option>
                            <option value="Airforce">Airforce</option>
                            <option value="Navy">Navy</option>
                            <option value="Commissioned">Commissioned</option>
                            <option value="Non-Commissioned">Non-Commissioned</option>
                        </select>
                    </div>
                    <!-- Add more fields as needed for recruitment -->
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>

            <div id="officers-on-duty" class="content">
                <h2>Officers on Duty</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Officer ID</th>
                                <th>Officer Name</th>
                                <th>Officer Rank</th>
                                <th>Officer Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fetch and display officers on duty from database -->
                <!-- Fetch and display officers on duty from database -->
                <?php
                if ($result_duty && $result_duty->num_rows > 0) {
                    while ($row = $result_duty->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['officer_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['rank'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No officers on duty found.</td></tr>";
                }
                ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="officers-on-leave" class="content">
                <h2>Officers on Leave</h2>
                <div class="table-container">
                    <table id="leaveTable">
                        <thead>
                            <tr>
                                <th>Officer ID</th>
                                <th>Officer Name</th>
                                <th>Leave Start Date</th>
                                <th>Leave End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be dynamically populated here -->
                                             <!-- Fetch and display officers on leave from database -->
                <?php
                if ($result_leave && $result_leave->num_rows > 0) {
                    while ($row = $result_leave->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['officer_id'] . "</td>";
                        echo "<td>" . $row['full_name'] . "</td>";
                        echo "<td>" . $row['leave_start'] . "</td>";
                        echo "<td>" . $row['leave_end'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No officers on leave found.</td></tr>";
                }
                ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="reports" class="content">
                <h2>Reports</h2>
                <form id="reportForm" method="POST" action="generate_report.php">
                    <div class="form-group">
                        <label for="reportType">Report Type</label>
                        <select id="reportType" name="reportType" required>
                            <option value="">Select Report</option>
                            <option value="all_officers">All Officers</option>
                            <option value="officers_on_leave">Officers on Leave</option>
                            <option value="officers_on_duty">Officers on Duty</option>
                            <option value="new_recruits">New Recruits</option>
                            <!-- Add more report types as needed -->
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Generate Report</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to handle form submission and ID generation
        document.addEventListener('DOMContentLoaded', function() {
            // Generate a random Officer ID
            document.getElementById('officerId').value = 'OFC' + Math.floor(Math.random() * 1000000);
            fetchOfficersOnLeave();
        });

        // Function to fetch officers on leave from database and update table
        function fetchOfficersOnLeave() {
            fetch('fetch_officers_on_leave.php') // Adjust URL based on your backend
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#leaveTable tbody');
                    tableBody.innerHTML = ''; // Clear existing rows

                    data.forEach(officer => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${officer.officer_id}</td>
                            <td>${officer.officer_name}</td>
                            <td>${officer.officer_rank}</td>
                            <td>${officer.officer_category}</td>
                            <td>${officer.leave_start_date}</td>
                            <td>${officer.leave_end_date}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching officers on leave:', error));
        }
        // JavaScript to handle navigation
        const sidebarLinks = document.querySelectorAll('.sidebar a');
        const contentSections = document.querySelectorAll('.main-content .content');

        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                sidebarLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                const target = this.getAttribute('href').substring(1);
                contentSections.forEach(section => {
                    section.style.display = section.id === target ? 'block' : 'none';
                });
            });
        });

        // Show the first content section by default
        contentSections.forEach((section, index) => {
            section.style.display = index === 0 ? 'block' : 'none';
        });

        // JavaScript for handling leave application success alert
        const leaveForm = document.getElementById('leaveForm');
        leaveForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Simulate a successful submission for demo purposes
            // Here you can perform form validation and AJAX request to process_leave.php

            // Display success alert
            alert("Leave application submitted successfully.");
            fetchOfficersOnLeave();
        });
        
    </script>
</body>
</html>
