<?php

require_once 'config.php';
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Query to get upcoming schedules for the current time
$upcomingSql = "SELECT * FROM tblschedule 
                WHERE STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') > '$currentDateTime'
                  AND STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') > NOW()
                ORDER BY STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') ASC";
$upcomingResult = $connection->query($upcomingSql);

// Count the number of upcoming schedules
$numUpcomingSchedules = $upcomingResult->num_rows;

// Display an image outside the table with a notification count and make it clickable
echo "<a href='viewschedule.php?class_id=$class_id'>
        <div style='position: relative; display: inline-block;'>
            <img src='bell.jpg' alt='Image' width='100' height='100' style='border-radius: 50%;'>
            <div style='position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; padding: 5px;'>$numUpcomingSchedules</div>
        </div>
      </a>";

// Display upcoming schedules in an HTML table with a bell icon
echo "<h2>Upcoming Schedules</h2>";
if ($numUpcomingSchedules > 0) {
    echo "<table border='1'>
            <tr>
                <th>Class ID</th>
                <th>Description</th>
                <th>Date</th>
                <th>Time</th>
                <th>Notification</th>
            </tr>";

    while ($row = $upcomingResult->fetch_assoc()) {
        $classId = $row['class_id'];
        $scheduleDate = $row['date'];

        // Convert 24-hour time to 12-hour format
        $scheduleTime = date("h:i A", strtotime($row['time']));

        $description = $row['description'];

        // Add a bell icon for notification
        $notificationIcon = '<i class="fa-solid fa-bell"></i>';

        echo "<tr>
                <td>$classId</td>
                <td>$description</td>
                <td>$scheduleDate</td>
                <td>$scheduleTime</td>
                <td>$notificationIcon</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No upcoming schedules for the current time.\n";
}

// Close the database connection
$connection->close();
?>
