<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete']) && isset($_GET['class_id'])) {
    $student_id = $_GET['delete'];
    $class_id = $_GET['class_id'];

    // Use both student_id and class_id in the DELETE query
    $delete_sql = "DELETE FROM tblstudents WHERE student_id='$student_id' AND class_id='$class_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
        
        // Redirect to managestudent.php after successful deletion
        header("Location: adminviewstudent.php?class_id=$class_id");
        exit(); // Make sure to exit to prevent further execution
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>
