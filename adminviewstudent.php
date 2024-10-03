<?php
include 'adminsidebar.php';
include 'adminnavbar.php';
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
          
            // Redirect to managestudent.php
            header("Location: adminviewstudent.php?class_id=$class_id");
            exit(); // Make sure that no further output is sent
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            overflow-x: auto; /* Enable horizontal scroll if needed */
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-top: 20px;
            border-radius: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
        }

        td a.delete:hover {
            text-decoration: underline;
        }

        .btn-container {
            text-align: left;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            border: 1px solid #4caf50;
        }

        .btn:hover {
            background-color: #45a049;
            border: 1px solid #45a049;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 100%;
            }

            .btn-container {
                text-align: center;
            }

            table {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Data</h1>

        <!-- Add Student Button -->
        <div class="btn-container">
            <a href="adminaddstudent.php?class_id=<?php echo $class_id; ?>" class="btn">Add Student</a>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Class ID</th>
                    <th>Delete</th>
                </tr>
                <?php $counter = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['fname']; ?></td>
                        <td><?php echo $row['middlename']; ?></td>
                        <td><?php echo $row['lastname']; ?></td>
                        <td><?php echo $row['class_id']; ?></td>
                        <td><a href="#" class="delete" onclick="confirmDelete('<?php echo $row['student_id']; ?>', '<?php echo $row['class_id']; ?>')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript for delete confirmation -->
    <script>
        function confirmDelete(studentId, classId) {
            var confirmDelete = confirm("Are you sure you want to delete this record?");
            if (confirmDelete) {
                window.location.href = 'admindelete.php?delete=' + studentId + '&class_id=' + classId;
            }
        }
    </script>
</body>
</html>