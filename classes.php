<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Get user_id from the session
$user_id = $_SESSION['user_id'];

// Establish a connection to the database
$result = mysqli_connect('localhost', 'root', '', 'classrecord');

// Check if the connection was successful
if (!$result) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch data from tblclass for the specific user_id
$query = "SELECT tblclass.*, tblsubjects.*, 
                 COUNT(CASE WHEN CONCAT(tblschedule.date, ' ', tblschedule.time) > NOW() THEN 1 END) AS upcoming_count
          FROM tblclass 
          JOIN tblsubjects ON (tblclass.course_number = tblsubjects.course_number) 
          LEFT JOIN tblschedule ON tblclass.class_id = tblschedule.class_id
          WHERE tblclass.user_id = '$user_id'
          GROUP BY tblclass.class_id";

$result1 = mysqli_query($result, $query);

// Define default values for viewRecordPage and tablePage
$viewRecordPage = '';
$tablePage = '';

// Define an array of LED theme colors
$ledColors = ['#6b38cc'];

// Start a container outside the loop
echo '<div class="container">';

// Counter to keep track of cards per row
$cardCount = 0;

// Start style block
echo '<style>';
echo '
.card {
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(107, 56, 204, 0.5);
}

.bg-purple {
    background-color: #6b38cc;
}

.text-white {
    color: #ffffff;
}

.card-footer a {
    margin-right: 10px;
}
';
// End style block
echo '</style>';

while ($row = mysqli_fetch_assoc($result1)) {
    // Start a new row every three cards
    if ($cardCount % 3 == 0) {
        echo '<div class="row">';
    }

    // Get the index for the LED color based on the card count
    $colorIndex = $cardCount % count($ledColors);
    $currentColor = $ledColors[$colorIndex];

    // Determine the table page based on conditions for Add Records button
    if ($row['laboratory'] == 0) {
        // If laboratory column value is 0 and class_course is BSIT, open nonlabBSITtable.php
        $tablePage = 'nonlabBSITtable.php';
    } elseif ($row['laboratory'] == 1) {
        // If laboratory column value is 1, open table.php
        $tablePage = 'table.php';
    }

    // Determine the view record page based on conditions
    if ($row['laboratory'] == 0) {
        // If laboratory column value is 0 and class_course is BSIT, open classrecordnonlabIT.php
        $viewRecordPage = 'classrecordnonlabIT.php';
    } elseif ($row['laboratory'] == 1) {
        // If laboratory column value is 1, open classrecordIT.php
        $viewRecordPage = 'classrecordIT.php';
    }

    // Display the card with a dynamic LED theme color
    echo '
    <div class="col-md-4 mb-4"> <!-- Adjusted spacing here -->
        <a href="viewschedule.php?class_id=' . $row['class_id'] . '" style="text-decoration: none; display: block; position: relative;">
            <div class="card bg-purple text-white" style="border: 2px solid ' . $currentColor . ';">
                <div style="position: absolute; top: 5px; right: 5px; z-index: 1;">
                    <img src="bell.jpg" alt="Image" width="40" height="40" style="border-radius: 50%;">
                    <div style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; padding: 3px;">' . $row['upcoming_count'] . '</div>
                </div>
                <div class="card-body">
                    <div class="card-header h2 text-dark">' . $row['course_number'] . '</div>
                    <p class="h5 mb-1"></p>
                    <p class="small m-0 text-dark">' . $row['class_course'] . ' ' . $row['class_year'] . '-' . $row['class_section'] . '</p>
                    <p class="small m-0 text-dark">' . $row['class_semester'] . '</p>
                    <p class="small m-0 text-dark">' . $row['school_year'] . '</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a class="btn btn-primary btn-sm" href="' . $viewRecordPage . '?class_id=' . $row['class_id'] . '">View Records</a>
                    <a class="btn btn-warning btn-sm" href="' . $tablePage . '?class_id=' . $row['class_id'] . '">Add Records</a>
                    <a class="btn btn-danger btn-sm" href="deletes.php">Delete</a>
                </div>
            </div>
        </a>
    </div>';

    // End the row every three cards
    if ($cardCount % 3 == 2 || $cardCount == mysqli_num_rows($result1) - 1) {
        echo '</div>';
    }

    // Increment the card count
    $cardCount++;
}

// Close the container
echo '</div>';

// Close the database connection
mysqli_close($result);
?>