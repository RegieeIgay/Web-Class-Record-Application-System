<?php
session_start();  // Start the session
require_once "config.php";
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d, Y h:i:s a');

// Assuming you have a mechanism to determine the current class_id
// For example, retrieving it from the URL parameter
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Retrieve user_id from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Initialize $raw_assessment_id
$raw_assessment_id = '';

// Variable to store the error message
$error_message = '';

if (isset($_POST['save'])) {
    // Generate a unique ID (you can customize this logic as needed)
    $raw_assessment_id = uniqid();
    $assessment_id = substr(hash('sha256', $raw_assessment_id), 0, 9);
    $assessment_title = $_POST['assesment_title'];
    $total_item = $_POST['total_item'];
    $term = $_POST['term'];
    $assessment_type = $_POST['assesment_type'];

    // Check if the assessment type allows only one assessment
    $allowed_assessment_types = array("Exam", "Output", "Behavior", "Participation");
    if (in_array($assessment_type, $allowed_assessment_types)) {
      // Check if there is already an assessment of this type for this class and term
        $query = "SELECT COUNT(*) AS count FROM tblassesment WHERE assesment_type = '$assessment_type' AND class_id = '$class_id' AND term = '$term'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $existing_count = $row['count'];

        if ($existing_count > 0) {
            $error_message = "Error: Only one '$assessment_type' is allowed per class for '$term'.";
        }

    }

    if (empty($error_message)) {
        // Insert the assessment into the database
        $sql = "INSERT INTO `tblassesment` (`assesment_id`, `assesment_title`, `total_item`, `assesment_type`, `term`, `class_id`, `user_id`, `datetime`) 
                VALUES ('$assessment_id', '$assessment_title', '$total_item', '$assessment_type', '$term', '$class_id', '$user_id', '$datetime')";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            // Redirect to the same page after successful submission
            header("Location: nonlabBSITtable.php?class_id=$class_id");
            exit;
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
            width: 900px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-left: 250px;
        }
    </style>


</head>

<body>
<?php 
include 'sidebar.php';
include 'navbar.php';
?>
    <div class="form-container">
        <!-- Display the error message if it exists -->
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action='addassesmentnonlabIT.php?class_id=<?php echo $class_id; ?>' method='post' onsubmit="return checkAssessmentTitle()">
            <!-- Your existing form content -->

            <input type='hidden' name='class_id' value='<?php echo $class_id; ?>'>

            <div class='mb-3'>    
                <label>Enter Assessment Title</label>
                <input type='text' class='form-control' id='assesment_title' name='assesment_title' placeholder='Enter Assessment Title'>
            </div>

            <div class='mb-3'>    
                <label>Enter Total Item</label>
                <input type='text' class='form-control' id='total_item' name='total_item' placeholder='Enter Total Item'>
            </div>

            <div class='mb-3'>
                <label>Assessment Type</label>
                <select class="form-select" id='assesment_type' name='assesment_type'>
                    <option>--Select Section--</option>
                    <option>Summative</option>
                    <option>Exam</option>
                    <option>Output</option>
                    <option>Participation</option>
                    <option>Activity</option>
                    <option>Assignment</option>
                    <option>Engagement </option>
                    <option>Behavior</option>              
                </select>
            </div>

            <div class='mb-3'>
                <label>Class Term</label>
                <select class="form-select" id='term' name='term'>
                    <option value="">--Select Term--</option>
                    <option value="MidTerm">MidTerm</option>
                    <option value="Final">Final</option>
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
