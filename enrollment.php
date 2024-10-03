<?php
session_start();  // Start the session
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('Y-m-d H:i:s');

// Function to sanitize and validate inputs
function sanitizeInput($input)
{
    global $connection;
    return mysqli_real_escape_string($connection, $input);
}

// Fetch distinct courses from the tbl_students table
$courses_query = "SELECT DISTINCT course FROM tblstudents";
$courses_result = $connection->query($courses_query);

// Variable to store the error message
$error_message = '';

if (isset($_POST['enroll'])) {
    // Validate and sanitize user inputs
    $year = sanitizeInput($_POST['year']);
    $section = sanitizeInput($_POST['section']);
    $course = sanitizeInput($_POST['course']);
    $school_year = sanitizeInput($_POST['school_year']);
    $semester = sanitizeInput($_POST['semester']);
    
    // Assuming you have the user input for class name and student name
    $class_name = isset($_POST['class_name']) ? $_POST['class_name'] : '';
    $student_name = isset($_POST['student_name']) ? $_POST['student_name'] : '';

    // Fetch class_id based on class name
    $class_query = "SELECT class_id FROM tblclass WHERE class_id = ?";
    $stmt_class = $connection->prepare($class_query);
    $stmt_class->bind_param("s", $class_name);
    $stmt_class->execute();
    $class_result = $stmt_class->get_result();

    if ($class_result->num_rows > 0) {
        $class_row = $class_result->fetch_assoc();
        $class_id = $class_row['class_id'];

        // Fetch student_id based on student name
        $student_query = "SELECT student_id FROM tblstudents WHERE student_id = ?";
        $stmt_student = $connection->prepare($student_query);
        $stmt_student->bind_param("s", $student_name);
        $stmt_student->execute();
        $student_result = $stmt_student->get_result();

        if ($student_result->num_rows > 0) {
            $student_row = $student_result->fetch_assoc();
            $student_id = $student_row['student_id'];

            // Use the fetched class_id and student_id in your insertion query
            $sql = "INSERT INTO tbl_student (class_id, subjectID, year, section, school_year, semester, enroll_date, course, student_id, fname, lastname, middlename, age) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $connection->prepare($sql);
            $stmt_insert->bind_param("iisssssssssd", $class_id, $subject_id, $year, $section, $school_year, $semester, $datetime, $course, $student_id, $fname, $lastname, $middlename, $age);

            // Assuming you have the values for fname, lastname, middlename, and age
            $fname = "John"; // Example value, replace with actual value
            $lastname = "Doe"; // Example value, replace with actual value
            $middlename = "Smith"; // Example value, replace with actual value
            $age = 25; // Example value, replace with actual value

            if ($stmt_insert->execute()) {
                // Redirect to the same page after successful submission
                header("Location: table.php?class_id=$class_id&subject_id=$subject_id");
                exit;
            } else {
                $error_message = "Error: " . $stmt_insert->error;
            }
        } else {
            $error_message = "Error: Student not found.";
        }
    } else {
        $error_message = "Error: Class not found.";
    }
}

// Close the database connection
$connection->close();
?>

<?php 
include 'sidebar.php';
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .form-container {
            margin-top: 0px;
            width: 900px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-left: 250px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form action='' method='post'> <!-- Removed action attribute -->
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Year and Academic Year Dropdown -->
            <div class='mb-3'>
                <label>Select Academic Year</label>
                <select class="form-select" name="year" id="year">
                    <?php 
                        // Generate options for academic year dropdown
                        for ($i = 1; $i <= 4; $i++) {
                            echo "<option value='$i'>{$i}st Year</option>";
                        }
                    ?>
                </select>
            </div>
            
            <!-- Section Dropdown -->
            <div class='mb-3'>
                <label>Select Section</label>
                <select class="form-select" name="section" id="section">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            
            <!-- Course Dropdown -->
            <div class='mb-3'>
                <label>Select Course</label>
                <select class="form-select" name="course" id="course">
                    <?php 
                        // Loop through courses and populate dropdown options
                        while ($row = $courses_result->fetch_assoc()) {
                            echo "<option value='{$row['course']}'>{$row['course']}</option>";
                        }
                    ?>
                </select>
            </div>
            
            <!-- School Year Dropdown -->
            <div class='mb-3'>
                <label>Select School Year</label>
                <select class="form-select" name="school_year" id="school_year">
                    <?php 
                        // Generate options for the school year dropdown
                        $currentYear = date('Y');
                        for ($i = $currentYear; $i >= 2018; $i--) {
                            $nextYear = $i + 1;
                            echo "<option value='$i-$nextYear'>$i-$nextYear</option>";
                        }
                    ?>
                </select>
            </div>
            
            <!-- Semester Dropdown -->
            <div class='mb-3'>
                <label>Select Semester</label>
                <select class="form-select" name="semester" id="semester">
                    <option value="1st">1st Semester</option>
                    <option value="2nd">2nd Semester</option>
                </select>
            </div>

            <!-- Class Name Input -->
            <div class='mb-3'>
                <label>Class Name</label>
                <input type="text" class="form-control" name="class_name" placeholder="Enter Class Name">
            </div>

            <!-- Student Name Input -->
            <div class='mb-3'>
                <label>Student Name</label>
                <input type="text" class="form-control" name="student_name" placeholder="Enter Student Name">
            </div>

            <br>
            <div class="">
                <button type='submit' name='enroll' class="btn btn-primary">Enroll Students</button>
                <br>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>