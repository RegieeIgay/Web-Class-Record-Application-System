<?php
require_once "config.php";


// Fetch the class_id and user_id
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the datetime value from the form
    $datetime = $_POST["datetime"];

    // Fetch all students for the specified class
    $sql_students = "SELECT student_id FROM tblstudents WHERE class_id = ?";
    $stmt_students = $connection->prepare($sql_students);
    $stmt_students->bind_param("s", $class_id);
    $stmt_students->execute();
    $result_students = $stmt_students->get_result();

    // SQL query to insert data into tblattendance for each student
    $sql_insert_attendance = "INSERT INTO tblattendance (class_id, student_id, datetime) VALUES (?, ?, ?)";
    $stmt_insert_attendance = $connection->prepare($sql_insert_attendance);
    $stmt_insert_attendance->bind_param("sss", $class_id, $student_id, $datetime);

    // Loop through each student and insert attendance record
    while ($row_student = $result_students->fetch_assoc()) {
        $student_id = $row_student['student_id'];
        $stmt_insert_attendance->execute();
    }

    // Redirect to attendance page after submitting the form
    header("Location: attendance.php?class_id=$class_id");
    exit(); // Ensure that the script stops executing after the header is sent
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
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
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .form-container {
            margin-top: 20px;
            width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Sidebar and Navbar included -->
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="form-container">
        <form action="addattendance.php?class_id=<?php echo $class_id; ?>" method="post">
            <h2>Attendance Form</h2>

            <label for="datetime">Attendance Datetime:</label>
            <input type="datetime-local" id="datetime" name="datetime" required>

            <br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
