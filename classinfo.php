<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$result = mysqli_connect('localhost', 'root', '', 'classrecord');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$result) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $userQuery = "SELECT * FROM tbluser WHERE user_id = '$user_id'";
    $userResult = mysqli_query($result, $userQuery);
    $userRow = mysqli_fetch_assoc($userResult);

    $classesQuery = "SELECT * FROM tblclass WHERE user_id = '$user_id'";
    $classesResult = mysqli_query($result, $classesQuery);
} else {
    header("Location: error.php?message=User ID not provided");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFILE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .profile-header {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 20px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #6a11cb;
            color: white;
        }

        .btn-success {
            background-color: #56ab2f;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    
    <?php include 'adminsidebar.php'; ?>
    <?php include 'adminnavbar.php'; ?>
    
    <div class="container mt-4">
        <br>
        <div class="profile-header p-4 rounded mb-4">
            <p><strong>INSTRUCTOR:</strong> <?php echo htmlspecialchars($userRow['Fullname']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($userRow['Department']); ?></p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Year</th>
                        <th>Section</th>
                        <th>Semester</th>
                        <th>Laboratory</th>
                        <th>Student</th>
                        <th>Assessment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($class = mysqli_fetch_assoc($classesResult)) : ?>
                    <tr>
                        <td><?php echo $class['class_course']; ?></td>
                        <td><?php echo $class['course_number']; ?></td>
                        <td><?php echo $class['class_year']; ?></td>
                        <td><?php echo $class['class_section']; ?></td>
                        <td><?php echo $class['class_semester']; ?></td>
                        <td><?php echo $class['laboratory']; ?></td>
                        <td>
                            <a href="adminviewstudent.php?class_id=<?php echo $class['class_id']; ?>" class="btn btn-primary"><i class="fa fa-users"></i> View Students</a>
                        </td>
                        <td>
                            <a href="adminviewassesment.php?class_id=<?php echo $class['class_id']; ?>" class="btn btn-success"><i class="fa fa-line-chart"></i> View Assessments</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php mysqli_close($result); ?>