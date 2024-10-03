<?php
session_start();
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d, Y h:i:s a');

// Assuming you have a user_id stored in the session

function sanitizeInput($input)
{
    global $connection;
    return mysqli_real_escape_string($connection, $input);
}

$error_message = '';

if (isset($_POST['save'])) {
    $course_number = sanitizeInput($_POST['course_number']);
    $course_description = sanitizeInput($_POST['course_description']);
    $user_id = sanitizeInput($_POST['user_id']);

    // Additional validation and sanitization if needed

    if (!empty($course_number) && !empty($course_description) && !empty($user_id)) {
        $query = "INSERT INTO `tbl_subjects` (`course_number`, `course_description`, `user_id`) 
                  VALUES ('$course_number', '$course_description', '$user_id')";

        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location: adminhome.php");
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($connection);
        }
    } else {
        $error_message = "Course number, description, or user ID cannot be empty.";
    }
}

// Fetch distinct user IDs from tbl_subjects excluding the admin's user ID
$admin_user_id = $_SESSION['user_id']; // Assuming you have a 'user_id' session variable
$query = "SELECT DISTINCT user_id FROM tbluser WHERE user_id <> '$admin_user_id'";
$result = $connection->query($query);

// Check if there are rows in the result
$options = [];
if ($result->num_rows > 0) {
    // Fetch all rows as an associative array
    $options = $result->fetch_all(MYSQLI_ASSOC);
}

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
                    <form action='adminaddsubject.php' method='post'>
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                        </div>
                        <?php endif; ?>
                        <div class='mb-3'>    
                            <label>Course Number</label>
                            <input type='text' class='form-control' id='course_number' name='course_number' placeholder='Enter Course Number'>
                        </div>

                        <div class='mb-3'>    
                            <label>Course Description</label>
                            <input type='text' class='form-control' id='course_description' name='course_description' placeholder='Enter  Course Description'>
                        </div>

                        
                            <div class='mb-3'>
                        <label>Select User ID</label>
                        <select name="user_id" id="user_id" class='form-select'>
                            <option>Select User ID</option>
                            <?php foreach ($options as $option) : ?>
                                <option><?php echo $option['user_id']; ?></option>
                            <?php endforeach; ?>
                        </select>
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
