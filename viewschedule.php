<?php 
include 'sidebar.php';
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Schedules</title>
    <style>
         * {
         font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 70%;
            text-align: center; /* Center align content within the container */
        }

        h1, h2 {
            text-align: center; /* Center align the headings */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 60px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        td a {
            color: #3498db;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        td a.delete {
            color: #e74c3c;
            text-decoration: none;
        }

        td a.delete:hover {
            text-decoration: underline;
        }

        form {
            margin-top: 20px;
            text-align: center; /* Center align the form */
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form select, form input {
            width: 70%; /* Adjust the width as needed */
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            margin: 0 auto; /* Center align the input */
        }

        form input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
        <?php
            // Include the database configuration file
            require_once 'config.php';

            // Function to get upcoming schedules
            function getUpcomingSchedules($class_id, $currentDateTime, $connection) {
                $stmt = $connection->prepare("SELECT * FROM tblschedule 
                                            WHERE class_id = ? 
                                            AND STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') > ? 
                                            AND STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') > NOW() 
                                            AND class_id = ?  
                                            ORDER BY STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') ASC");

                $stmt->bind_param("iis", $class_id, $currentDateTime, $class_id);
                $stmt->execute();

                return $stmt->get_result();
            }

            // Get the class_id from the URL parameter
            $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

            // Check if class_id is provided
            if (!$class_id) {
                echo "Please provide a valid class_id.";
                exit; // Stop further execution
            }

            // Get the current date and time
            $currentDateTime = date('Y-m-d H:i:s');

            // Get the result set
            $upcomingResult = getUpcomingSchedules($class_id, $currentDateTime, $connection);

            // Display upcoming schedules for the specified class_id in an HTML table
            echo "<h2>UPCOMING SCHEDULES</h2>";
            if ($upcomingResult->num_rows > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Description</th>
                            <th>Location</th>
                        </tr>";

                // Loop through the result set and display schedule details
                while ($row = $upcomingResult->fetch_assoc()) {
                    $scheduleDate = $row['date'];
                    $scheduleTime = date("h:i A", strtotime($row['time']));
                    $description = $row['description'];
                    $location = $row['location']; // Add this line to fetch location

                    echo "<tr>
                            <td>$scheduleDate</td>
                            <td>$scheduleTime</td>
                            <td>$description</td>
                            <td>$location</td> 
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "No upcoming schedules for this Class.\n";
            }

            // Close the database connection
            $connection->close();
        ?>
    </div>

</body>
</html>