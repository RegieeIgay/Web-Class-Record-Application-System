<?php
session_start();  // Start the session
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d, Y h:i:s a');

// Function to generate random alphanumeric string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['save'])) {
        // Generate random class ID
        $class_id = generateRandomString(10);

        // Retrieve other form data
        $course_number = $_POST['course_number'];
        $class_year = $_POST['class_year'];
        $class_section = $_POST['class_section'];
        $school_year = $_POST['school_year'];
        $class_course = $_POST['class_course'];
        $class_semester = $_POST['class_semester'];
        $lab_option = $_POST['laboratory']; // New input for laboratory option

        // Set laboratory value based on the user's selection
        $lab = ($lab_option == 'laboratory') ? 1 : 0;

        // Map lab_option value to 'Non-Laboratory' or 'Laboratory'
        $lab_type = ($lab_option == 'non_lab') ? 'Non-Laboratory' : 'Laboratory';

        // Prepare and execute SQL query
        $sql = "INSERT INTO `tblclass`(`class_id`, `course_number`, `class_semester`, `class_course`, `class_year`,  `class_section`, `school_year`, `user_id`, `datetime`, `laboratory`) 
        VALUES ('$class_id','$course_number','$class_semester','$class_course','$class_year','$class_section','$school_year','$user_id','$datetime', '$lab_type')";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            // Check if the class is a laboratory or non-laboratory and redirect accordingly
            if ($lab == 1) {
                header("Location: home.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch data from tbl_subjects
$query = "SELECT DISTINCT course_number , class_course , semester , year FROM tblsubjects";
$result = $connection->query($query);

// Check if there are rows in the result
$options = [];
if ($result->num_rows > 0) {
    // Fetch all rows as an associative array
    $options = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Close the database connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'sidebar.php'; ?>
    <?php include 'navbar.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            outline: 0;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <br>
        <h3>CREATE CLASS</h3>
        <form action="createclass.php" method="post">
           
        <div class="form-group">
    <label for="class_course">Select Class Course</label>
    <select class="form-control" name="class_course" id="class_course">
        <option disabled selected>Select Course</option>
        <option value="BSIT">BSIT</option>
        <option value="BSAB">BSAB</option>
        <option value="BSHM">BSHM</option>
        <option value="BEED">BEED</option>
        <option value="BSED">BSED</option>
    </select>
</div>

<div class="form-group">
    <label for="class_semester">Select Class Semester</label>
    <select class="form-control" name="class_semester" id="class_semester">
        <option disabled selected>Select Semester</option>
        <option>First Semester</option>
        <option>Second Semester</option>
    </select>
</div>

<div class="form-group">
    <label for="class_year">Class Year</label>
    <select class="form-control" name="class_year" id="class_year">
        <option disabled selected>Select Year</option>
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
    </select>
</div>

<div class="form-group">
    <label for="course_number">Select Subject Number</label>
    <select class="form-control" name="course_number" id="course_number">
        <option disabled selected>Select Subject Number</option>
        <?php 
        // Loop through options and display only those for the selected course, semester, and year
        foreach ($options as $option) {
            // Check if the course, semester, and year of the current option match the selected course, semester, and year
            if ($option['class_course'] == $_POST['class_course'] && 
                $option['class_semester'] == $_POST['class_semester'] && 
                $option['class_year'] == $_POST['class_year']) {
                echo "<option>{$option['course_number']}</option>";
            }
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label for="class_section">Class Section</label>
    <select class="form-control" name="class_section" id="class_section">
        <option disabled selected>Select Section</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
    </select>
</div>

<div class="form-group">
    <label for="school_year">School Year</label>
    <select class="form-control" name="school_year" id="school_year">
        <option disabled selected>Select School Year</option>
        <?php
        // Generate the range of school years dynamically
        $start_year = date("Y") - 1;
        $end_year = date("Y");
        for ($year = $start_year; $year <= $end_year; $year++) {
            $next_year = $year + 1;
            echo "<option value=\"$year-$next_year\">$year-$next_year</option>";
        }
        ?>
    </select>
</div>

<!-- Hidden inputs for class_id and user_id -->
<input type="hidden" name="class_id" value="<?php echo generateRandomString(10); ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

<button type="submit" name="save" class="btn btn-primary">Submit</button>
</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
document.getElementById('class_course').addEventListener('change', function() {
    updateOptions();
});

document.getElementById('class_semester').addEventListener('change', function() {
    updateOptions();
});

document.getElementById('class_year').addEventListener('change', function() {
    updateOptions();
});

function updateOptions() {
    var selectedCourse = document.getElementById('class_course').value;
    var selectedSemester = document.getElementById('class_semester').value;
    var selectedYear = document.getElementById('class_year').value;
    var options = <?php echo json_encode($options); ?>;
    var courseNumbers = document.getElementById('course_number');
    courseNumbers.innerHTML = ''; // Clear existing options

    // Add options for the selected course, semester, and year
    options.forEach(function(option) {
        if (option.class_course === selectedCourse && option.semester === selectedSemester && option.year === selectedYear) {
            var optionElement = document.createElement('option');
            optionElement.textContent = option.course_number;
            courseNumbers.appendChild(optionElement);
        }
    });
}

// Initial update when the page loads
updateOptions();
</script>
</body>

</html>
