<?php
require_once "config.php";

// Check if the form is submitted for updating the assessment record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_assessment'])) {
    $assessment_id = $_POST['assesment_id'];
    $description = $_POST['assesment_title'];
    $assessment_type = $_POST['assesment_type'];
    $total_item = $_POST['total_item'];
    $term = $_POST['term']; // Add term variable
    $class_id = $_POST['class_id'];

    // Proceed with the update
    $update_assessment_sql = "UPDATE tblassesment SET assesment_title='$description', assesment_type='$assessment_type', total_item='$total_item', term='$term' WHERE assesment_id='$assessment_id'";

    if ($connection->query($update_assessment_sql) === TRUE) {
        // Redirect to this page to refresh the assessments table
        header("Location: {$_SERVER['PHP_SELF']}?class_id=$class_id");
        exit();
    } else {
        echo "Error updating assessment record: " . $connection->error;
    }
} elseif (isset($_GET['delete_assessment'])) {
    // Handle assessment deletion
    $delete_assessment_id = $_GET['delete_assessment'];
    $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

    // Delete the assessment with the specified ID and class ID
    $delete_assessment_sql = "DELETE FROM tblassesment WHERE assesment_id='$delete_assessment_id' AND class_id='$class_id'";
    if ($connection->query($delete_assessment_sql) === TRUE) {
        // Redirect to this page to refresh the assessments table
        header("Location: {$_SERVER['PHP_SELF']}?class_id=$class_id");
        exit();
    } else {
        echo "Error deleting assessment record: " . $connection->error;
    }
}

// Assuming you have a mechanism to determine the current class_id
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Modify the SQL query to select assessments only for the specific class_id
$assessment_sql = "SELECT * FROM tblassesment WHERE class_id='$class_id'";
$assessment_result = $connection->query($assessment_sql);

// Select all assessments for the edit form
$edit_assessment_result = $connection->query("SELECT assesment_id, assesment_title, assesment_type, total_item, term, class_id FROM tblassesment");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Data</title>
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
                font-family: Arial, sans-serif;
            }

            .container {
                width: 70%;
                text-align: center; /* Center align content within the container */
            }

            h1, h2 {
                text-align: center; /* Center align the headings */
            }

            table {
                border-collapse: collapse;
                width: 90%;
                margin-top: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-left: 170px;
            }

            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 12px;
            }

            th {
                background-color: #f2f2f2;
            }

            td a {
                color: #3498db;
                text-decoration: none;
            }

            td a:hover {
                text-decoration: underline;
            }

            td a.delete {
                color: #e74c3c;
                text-decoration: none;
            }

            td a.delete:hover {
                text-decoration: underline;
            }

            form {
                margin-top: 20px;
                text-align: center; /* Center align the form */
            }

            form label {
                display: block;
                margin-bottom: 8px;
            }

            form select, form input {
                width: 70%; /* Adjust the width as needed */
                padding: 8px;
                margin-bottom: 16px;
                box-sizing: border-box;
                margin: 0 auto; /* Center align the input */
            }

            form input[type="submit"] {
                background-color: #4caf50;
                color: white;
                cursor: pointer;
            }

            form input[type="submit"]:hover {
                background-color: #45a049;
            }
        </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<?php include 'navbar.php'; ?>

<!-- Assessments Table -->
<?php
if ($assessment_result) {
    if ($assessment_result->num_rows > 0) {
        echo '<div class="container">';
        echo '<h2>Assessments</h2>';
        echo '<table>';
        echo '<tr><th>Assessment ID</th><th>Assessment Title</th><th>Assessment Type</th><th>Total Items</th><th>Term</th><th>Class ID</th><th>Edit</th><th>Delete</th></tr>';
        while ($row = $assessment_result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['assesment_id'] . '</td>';
            echo '<td>' . $row['assesment_title'] . '</td>';
            echo '<td>' . $row['assesment_type'] . '</td>';
            echo '<td>' . $row['total_item'] . '</td>';
            echo '<td>' . $row['term'] . '</td>';
            echo '<td>' . $row['class_id'] . '</td>';
            echo '<td><a href="?edit_assessment=' . $row['assesment_id'] . '">Edit</a></td>';
            echo '<td><a href="#" class="delete-link" data-assessment-id="' . $row['assesment_id'] . '" data-class-id="' . $row['class_id'] . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    $assessment_result->free();
} else {
    echo "Error: " . $assessment_sql . "<br>" . $connection->error;
}
?>

<!-- Add JavaScript for confirmation -->
<script>
    // Use JavaScript to prompt for confirmation before deleting
    document.addEventListener('DOMContentLoaded', function () {
        var deleteLinks = document.querySelectorAll('.delete-link');

        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                var assessmentId = link.getAttribute('data-assessment-id');
                var classId = link.getAttribute('data-class-id');

                var confirmDelete = confirm('Are you sure you want to delete this assessment?');

                if (confirmDelete) {
                    // If user confirms, redirect to the delete URL
                    window.location.href = '?delete_assessment=' + assessmentId + '&class_id=' + classId;
                }
            });
        });
    });
</script>

<!-- Assessments Form -->
<div class="container">
    <?php
    // Display the edit form if the edit_assessment parameter is present in the URL
    if (isset($_GET['edit_assessment'])) {
        $edit_assessment_id = $_GET['edit_assessment'];
        $edit_assessment_query = "SELECT * FROM tblassesment WHERE assesment_id='$edit_assessment_id'";
        $edit_assessment_result = $connection->query($edit_assessment_query);
        $edit_assessment_row = $edit_assessment_result->fetch_assoc();

        // Display the edit form for assessments
        ?>
        <div class="form">
            <form method="post" action="">
                <input type="hidden" name="assesment_id" value="<?php echo $edit_assessment_row['assesment_id']; ?>">
                <label for="assessment_title">Assessment Title:</label>
                <input type="text" name="assesment_title" value="<?php echo $edit_assessment_row['assesment_title']; ?>"><br>

                <!-- Replace the existing label and input for Assessment Type with a dropdown menu -->
                <label for="assesment_type">Assessment Type:</label>
                <select name="assesment_type" class="styled-select">
                    <?php
                    $assessment_types = array("Summative", "Exam", "Output", "Laboratory", "Assignment", "Participation", "Behavior"); // Add more types as needed
                    foreach ($assessment_types as $type) {
                        $selected = ($edit_assessment_row['assesment_type'] == $type) ? 'selected' : '';
                        echo "<option value=\"$type\" $selected>$type</option>";
                    }
                    ?>
                </select><br>

                <label for="total_item">Total Items:</label>
                <input type="text" name="total_item" value="<?php echo $edit_assessment_row['total_item']; ?>"><br>

                <label for="term">Term:</label>
                <select name="term" class="styled-select">
                    <?php
                    $term_options = array("Final", "Midterm"); // Add more terms as needed
                    foreach ($term_options as $term_option) {
                        $selected = ($edit_assessment_row['term'] == $term_option) ? 'selected' : '';
                        echo "<option value=\"$term_option\" $selected>$term_option</option>";
                    }
                    ?>
                </select><br>



                <input type="hidden" name="class_id" value="<?php echo $edit_assessment_row['class_id']; ?>">
                <input type="submit" name="update_assessment" value="Update Assessment">
            </form>
        </div>
        <?php
    }
    ?>
</div>

</body>
</html>