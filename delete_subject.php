<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Use only user_id in the DELETE query
    $delete_sql = "DELETE FROM tbl_subjects WHERE user_id='$user_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";

        // Redirect to some page after successful deletion
        header("Location: adminviewsubjects.php");
        exit(); // Make sure to exit to prevent further execution
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>
