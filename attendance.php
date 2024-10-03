<?php
require_once "config.php";


$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming your form fields are named 'record'
    $records = isset($_POST['record']) ? $_POST['record'] : [];

    // Iterate through the records and insert/update them in the tblattendance table
    foreach ($records as $student_id => $recordValues) {
        foreach ($recordValues as $datetimeValue => $record) {
            // Check if the record already exists
            $sql_existing_record = "SELECT * FROM tblattendance WHERE class_id = ? AND student_id = ? AND datetime = ?";
            $stmt_existing_record = $connection->prepare($sql_existing_record);
            $stmt_existing_record->bind_param("sss", $class_id, $student_id, $datetimeValue);
            $stmt_existing_record->execute();
            $result_existing_record = $stmt_existing_record->get_result();

            if ($result_existing_record->num_rows > 0) {
                // Update the existing record
                $sql_update_record = "UPDATE tblattendance SET record = ? WHERE class_id = ? AND student_id = ? AND datetime = ?";
                $stmt_update_record = $connection->prepare($sql_update_record);
                $stmt_update_record->bind_param("ssss", $record, $class_id, $student_id, $datetimeValue);
                $stmt_update_record->execute();
            } else {
                // Insert a new record
                $sql_insert_record = "INSERT INTO tblattendance (class_id, student_id, datetime, record) VALUES (?, ?, ?, ?)";
                $stmt_insert_record = $connection->prepare($sql_insert_record);
                $stmt_insert_record->bind_param("ssss", $class_id, $student_id, $datetimeValue, $record);
                $stmt_insert_record->execute();
            }
        }
    }
}

$sql_students = "SELECT * FROM tblstudents WHERE class_id = ?";
$stmt_students = $connection->prepare($sql_students);
$stmt_students->bind_param("s", $class_id);
$stmt_students->execute();
$result_students = $stmt_students->get_result();

$sql_attendance = "SELECT DISTINCT datetime FROM tblattendance WHERE class_id = ?";
$stmt_attendance = $connection->prepare($sql_attendance);
$stmt_attendance->bind_param("s", $class_id);
$stmt_attendance->execute();
$result_attendance = $stmt_attendance->get_result();

$uniqueDatetime = array();

$addAssessmentLink = "addassesment.php?class_id=$class_id&user_id=$user_id";
$addStudentLink = "addstudent.php?class_id=$class_id&user_id=$user_id";
$addAttendance = "addattendance.php?class_id=$class_id&user_id=$user_id";
$averageattendance = "attendanceaverage.php?class_id=$class_id&user_id=$user_id";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            width: 95%;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        select {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .buttons {
            text-align: right;
            margin-top: 10px;
        }
        .btn {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <?php include 'navbar.php'; ?>

        <div class="container">
            <div class="buttons">
                <a href="<?php echo $addAttendance; ?>" class="btn">Add Attendance</a>
                <a href="<?php echo $averageattendance ?>" class="btn">Average</a>
            </div>
            <form action="" method="post">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <table>
                    <tr>
                        <th>Names</th>
                        <?php
                        while ($row_attendance = $result_attendance->fetch_assoc()) {
                            $datetimeValue = $row_attendance["datetime"];
                            echo "<th>" . $datetimeValue . "</th>";
                            $uniqueDatetime[] = $datetimeValue;
                        }
                        ?>
                    </tr>
                    <?php
                    while ($row_student = $result_students->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_student["fname"] . " " . $row_student["lastname"] . "</td>";
                        foreach ($uniqueDatetime as $datetimeValue) {
                            echo "<td>";
                            $sql_get_record = "SELECT record FROM tblattendance WHERE class_id = ? AND student_id = ? AND datetime = ?";
                            $stmt_get_record = $connection->prepare($sql_get_record);
                            $stmt_get_record->bind_param("sss", $class_id, $row_student['student_id'], $datetimeValue);
                            $stmt_get_record->execute();
                            $result_get_record = $stmt_get_record->get_result();
                            $row_get_record = $result_get_record->fetch_assoc();
                            $record_value = isset($row_get_record['record']) ? $row_get_record['record'] : '';
                            echo "<select name='record[" . $row_student['student_id'] . "][$datetimeValue]'>";
                            echo "<option value='PRESENT' " . ($record_value == 'PRESENT' ? 'selected' : '') . ">PRESENT</option>";
                            echo "<option value='ABSENT' " . ($record_value == 'ABSENT' ? 'selected' : '') . ">ABSENT</option>";
                            echo "<option value='EXCUSED' " . ($record_value == 'EXCUSED' ? 'selected' : '') . ">EXCUSED</option>";
                            echo "</select>";
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </table>
                <div class="buttons">
                    <button type="submit" class="btn">SAVE RECORDS</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>