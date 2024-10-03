<?php
session_start();  // Start the session
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d, Y h:i:s a');

// Retrieve user_id from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Variable to store the success message
$success_message = '';

// Variable to store the error message
$error_message = '';

if (isset($_POST['save'])) {
    // Retrieve course title from the form
    $course_title = $_POST['course_title'];

    // Check if the course already exists
    $check_sql = "SELECT * FROM `tbl_course` WHERE `course_title` = '$course_title'";
    $check_result = mysqli_query($connection, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Course already exists, display error message
        $error_message = "Error: Course already exists.";
    } else {
        // Insert the course into the database
        $sql = "INSERT INTO `tbl_course` (`course_title`) VALUES ('$course_title')";
        $result_course = mysqli_query($connection, $sql);

        if ($result_course) {
            // Set the success message
            $success_message = "Course successfully added.";
        } else {
            $error_message = "Error: " . mysqli_error($connection);
        }
    }
}

// Close the database connection
$connection->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .form-container {
            margin-top: 50px;
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
   
</head>
<body>
<?php 
include 'adminsidebar.php';
include 'adminnavbar.php';
?>
    <div class="form-container">
        <!-- Display the success message if it exists -->
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
                <script>showSuccessMessage("<?php echo $success_message; ?>");</script> <!-- Call JavaScript function to show message -->
            </div>
        <?php endif; ?>

        <!-- Display the error message if it exists -->
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">    
                <label>Enter Course Title</label>
                <input type="text" class="form-control" name="course_title" placeholder="Enter Course Title" required>
            </div>

            <div class="">
                <button type="submit" name="save" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
