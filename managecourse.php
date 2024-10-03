<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for deleting the record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $delete_id = $_POST['delete'];
    $delete_sql = "DELETE FROM tbl_course WHERE id='$delete_id'";

    if ($conn->query($delete_sql) === TRUE) {
        
    } else {
        echo "Error deleting course: " . $conn->error;
    }
}

// Select all courses
$sql = "SELECT id, course_title FROM tbl_course";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
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
            margin-top: 270px;
        }
        h2 {
           text-align:center;
        }

        table {
            
            border-collapse: collapse;
            width: 60%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto; /* Center the table horizontally */
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
</head>
<body>
<?php 
include 'adminsidebar.php';
include 'adminnavbar.php';
?>
<div class="container">
    <h2>Manage Courses</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Course Title</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            $row_number = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_number . "</td>";
                echo "<td>" . $row['course_title'] . "</td>";
                echo "<td>";
                // Add JavaScript confirmation
                echo "<form method='post' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='delete' value='" . $row['id'] . "'>";
                echo "<input type='submit' value='Delete'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
                $row_number++;
            }
        } else {
            echo "<tr><td colspan='3'>No courses found</td></tr>";
        }
        ?>
    </table>
</div>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this course?");
    }
</script>
</body>
</html>