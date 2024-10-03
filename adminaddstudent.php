<?php
session_start();  // Start the session
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d, Y h:i:s a');

// Assuming you have a mechanism to determine the current class_id
// For example, retrieving it from the URL parameter
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Function to sanitize and validate inputs
function sanitizeInput($input)
{
    global $connection;
    return mysqli_real_escape_string($connection, $input);
}

// Variable to store the error message
$error_message = '';

if (isset($_POST['save'])) {
    // Validate and sanitize user inputs
    $student_id = sanitizeInput($_POST['student_id']);
    $fname = sanitizeInput($_POST['fname']);
    $lastname = sanitizeInput($_POST['lastname']);
    $middlename = sanitizeInput($_POST['middlename']);
    $age = sanitizeInput($_POST['age']);

    if (!empty($student_id)) {
        // Check if the student ID already exists for the given class
        $query = "SELECT COUNT(*) AS count FROM tblstudents WHERE student_id = '$student_id' AND class_id = '$class_id'";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $idExists = $row['count'] > 0;

            if ($idExists) {
                $error_message = "Error: Student ID already exists for this class. Please choose a different ID.";
            } else {
                // Use prepared statement to insert data
                $sql = "INSERT INTO `tblstudents` (`student_id`, `fname`, `lastname`, `middlename`, `age`, `class_id`) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("ssssis", $student_id, $fname, $lastname, $middlename, $age, $class_id);
            
                if ($stmt->execute()) {
                    // Redirect to the same page after successful submission
                    header("Location: adminviewstudent.php?class_id=$class_id");
                    exit;
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            }
        } else {
            $error_message = "Error checking student ID: " . mysqli_error($connection);
        }
    } else {
        $error_message = "Student ID cannot be empty.";
    }
}

// Close the database connection
$connection->close();
?>

<?php 
include 'adminsidebar.php';
include 'adminnavbar.php';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Class</title>
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
                <form action='adminaddstudent.php?class_id=<?php echo $class_id; ?>' method='post'>
                <?php if (!empty($error_message)) : ?>
                    <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>
                    <div class='mb-3'>    
                        <label>Enter Student ID</label>
                        <input type='text' class='form-control' id='student_id' name='student_id' placeholder='Enter Student ID'>
                    </div>

                    <div class='mb-3'>    
                        <label>Enter Firstname</label>
                        <input type='text' class='form-control' id='fname' name='fname' placeholder='Enter Firstname'>
                    </div>

                    <div class='mb-3'>    
                        <label>Enter Lastname</label>
                        <input type='text' class='form-control' id='lastname' name='lastname' placeholder='Enter Lastname'>
                    </div>

                    <div class='mb-3'>    
                        <label>Enter Middle Name</label>
                        <input type='text' class='form-control' id='middlename' name='middlename' placeholder='Enter Middle Name'>
                    </div>
                    <div class='mb-3'>    
                        <label>Enter Age</label>
                        <input type='text' class='form-control' id='age' name='age' placeholder='Enter Age'>
                    </div>

                    <br>
                    <div class="">
                        <button type='submit' name='save' class="btn btn-primary">Submit</button>
                        <br>
                    </div>
                </form>
            </div>
        

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>

</html>
