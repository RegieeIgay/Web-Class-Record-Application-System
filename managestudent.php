<?php
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for updating or deleting the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Code for updating the record
        $student_id = $_POST['student_id'];
        $fname = $_POST['fname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];

        $update_sql = "UPDATE tblstudents SET fname='$fname', middlename='$middlename', lastname='$lastname' WHERE student_id='$student_id'";

        if ($conn->query($update_sql) === TRUE) {
           
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        // Code for deleting the record
        $delete_id = $_POST['delete'];
        $delete_sql = "DELETE FROM tblstudents WHERE student_id='$delete_id'";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

// Assuming you have a mechanism to determine the current class_id
// For example, retrieving it from the URL parameter
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Modify the SQL query to select students only for the specific class_id
$sql = "SELECT student_id, fname, middlename, lastname, class_id FROM tblstudents WHERE class_id='$class_id'";
$result = $conn->query($sql);

// Select all students for the edit form
$edit_result = $conn->query("SELECT student_id, fname, middlename, lastname FROM tblstudents");

?>

<!DOCTYPE html>
<html lang="en">
         <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Student Data</title>
        
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
                            }

                            table {
                                border-collapse: collapse;
                                width: 100%;
                                margin-left: 110px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
                                color: #e74c3c; /* Red color for delete link */
                                text-decoration: none;
                            }

                            td a.delete:hover {
                                text-decoration: underline;
                            }

                            form {
                                margin-top: 20px;
                            }

                            form label {
                                display: block;
                                margin-bottom: 8px;
                            }

                            form input {
                                width: 100%;
                                padding: 8px;
                                margin-bottom: 16px;
                                box-sizing: border-box;
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
            <!-- JavaScript for delete confirmation -->
            <script>
                function confirmDelete(studentId, classId) {
                    var confirmDelete = confirm("Are you sure you want to delete this student?");
                    if (confirmDelete) {
                        window.location.href = 'delete.php?delete=' + studentId + '&class_id=' + classId;
                    }
                }
            </script>
        </head>

                
        <body>
 <?php 
include 'sidebar.php';
include 'navbar.php';
?>
                    <?php
                    if ($result) {
                        if ($result->num_rows > 0) {
                            echo '<div class="container">';
                            echo '<table>';
                            echo '<tr><th>#</th><th>Student ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Class ID</th><th>Edit</th><th>Delete</th></tr>';
                            $row_number = 1; // Initialize the row number
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row_number . '</td>';
                                echo '<td>' . $row['student_id'] . '</td>';
                                echo '<td>' . $row['fname'] . '</td>';
                                echo '<td>' . $row['middlename'] . '</td>';
                                echo '<td>' . $row['lastname'] . '</td>';
                                echo '<td>' . $row['class_id'] . '</td>';
                                echo '<td><a href="?edit=' . $row['student_id'] . '">Edit</a></td>';
                                // Add the onclick attribute for the delete link
                                echo '<td><a href="#" class="delete-link" onclick="confirmDelete(\'' . $row['student_id'] . '\', \'' . $row['class_id'] . '\')">Delete</a></td>';
                                echo '</tr>';
                                $row_number++; // Increment the row number for the next iteration
                            }
                            echo '</table>';
                        } 

                        $result->free();
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    // Display the edit form if the edit parameter is present in the URL
                    if (isset($_GET['edit'])) {
                        $edit_id = $_GET['edit'];
                        $edit_query = "SELECT student_id, fname, middlename, lastname FROM tblstudents WHERE student_id='$edit_id'";
                        $edit_result = $conn->query($edit_query);

                        // Display the edit form
                        if ($edit_result && $edit_result->num_rows > 0) {
                            $edit_row = $edit_result->fetch_assoc();
                            ?>
                            <div class="form">
                                <form method="post" action="">
                                    <input type="hidden" name="student_id" value="<?php echo $edit_row['student_id']; ?>">
                                    <label for="fname">First Name:</label>
                                    <input type="text" name="fname" value="<?php echo $edit_row['fname']; ?>"><br>
                                    <label for="middlename">Middle Name:</label>
                                    <input type="text" name="middlename" value="<?php echo $edit_row['middlename']; ?>"><br>
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" name="lastname" value="<?php echo $edit_row['lastname']; ?>"><br>
                                    <input type="submit" name="update" value="Update">
                                </form>
                            </div>
                        <?php
                        } else {
                            echo "<p>Error: Unable to fetch student data for editing.</p>";
                        }
                    }

                    $conn->close();
                    ?>

        </body>
        </html>
