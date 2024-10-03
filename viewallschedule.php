
<?php
require_once "config.php";

// Assuming you have a mechanism to determine the current class_id
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Modify the SQL query to select schedule data for the specific class_id
$schedule_sql = "SELECT * FROM tblschedule WHERE class_id='$class_id' ORDER BY date ASC";
$schedule_result = $connection->query($schedule_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTS</title>
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
                font-family: Arial, sans-serif;
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
                width: 80%;
                margin-top: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-left: 190px;
            }

            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 12px;
            }

            th {
                background-color: #f2f2f2;
            }

         
        </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<?php include 'navbar.php'; ?>
<!-- Schedule Table -->
<?php
if ($schedule_result) {
    echo '<div class="container">';
    echo '<h2>Schedule</h2>';
    echo '<table>';
    echo '<tr><th>Title</th><th>Date</th><th>Time</th><th>Location</th></tr>';
    while ($row = $schedule_result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td>' . $row['date'] . '</td>';
        echo '<td>' . $row['time'] . '</td>';
        echo '<td>' . $row['location'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';

    $schedule_result->free();
} else {
    echo "Error: " . $schedule_sql . "<br>" . $connection->error;
}
?>

</body>
</html>
