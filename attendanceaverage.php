<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average Attendance</title>
    <style>
        body,
        .navbar-nav .nav-link,
        .average-container,
        table,
        th,
        td {
            font-family: Arial, sans-serif;
        }

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
        }

        .average-container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

    </style>
</head>

<body>

    <?php
    require_once "config.php";
    include 'sidebar.php';
    include 'navbar.php';

    // Fetch the class_id
    $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

    // Fetch attendance records with student names
    $sql_attendance = "
            SELECT 
                a.student_id, 
                s.fname, 
                s.lastname, 
                COUNT(*) as total_records, 
                SUM(CASE WHEN a.record IN ('present', 'excused') THEN 1 ELSE 0 END) as total_present 
            FROM 
                tblattendance a
            JOIN 
                tblstudents s ON a.student_id = s.student_id
            WHERE 
                a.class_id = ? 
            GROUP BY 
                a.student_id, s.fname, s.lastname
        ";
    $stmt_attendance = $connection->prepare($sql_attendance);
    $stmt_attendance->bind_param("s", $class_id);
    $stmt_attendance->execute();
    $result_attendance = $stmt_attendance->get_result();

    // Initialize variables for calculating average
    $studentAverages = [];

    // Loop through each student
    while ($row = $result_attendance->fetch_assoc()) {
        $student_id = $row['student_id'];
        $fname = $row['fname'];
        $lastname = $row['lastname'];
        $totalRecords = $row['total_records'];
        $totalPresent = $row['total_present'];

        // Calculate average attendance as a percentage
        $averageAttendance = $totalRecords > 0 ? ($totalPresent / $totalRecords) * 100 : 0;

        // Store the average attendance for each student
        $studentAverages[$student_id] = ['fname' => $fname, 'lastname' => $lastname, 'averageAttendance' => $averageAttendance];
    }
    ?>

    <div class="average-container">
        <h2>Average Attendance</h2>
        <p>Class ID: <?php echo $class_id; ?></p>

        <table>
            <tr>
                <th>Names</th>
                <th>Average Attendance</th>
            </tr>
            <?php foreach ($studentAverages as $student_id => $data) : ?>
                <tr>
                    <td><?php echo $data['fname'] . ' ' . $data['lastname']; ?></td>
                    <td><?php echo $data['averageAttendance'] . "%"; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>
