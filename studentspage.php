<?php
require_once "config.php";
include 'sidebar.php';
include 'navbar.php';

// Assuming you have a mechanism to determine the current class_id and user_id
// For example, retrieving them from the session
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming your form fields are named 'scores'
    $scores = isset($_POST['scores']) ? $_POST['scores'] : [];

    // Iterate through the scores and insert/update them in the tblstdassesment table
    foreach ($scores as $student_id => $assessments) {
        foreach ($assessments as $assessment_id => $score) {
            // Get or insert assessment_id based on title
            $sql_assessment = "SELECT assesment_id FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
            $stmt_assessment = $connection->prepare($sql_assessment);
            $stmt_assessment->bind_param("ss", $assessment_id, $class_id);
            $stmt_assessment->execute();
            $result_assessment = $stmt_assessment->get_result();

            if ($result_assessment->num_rows > 0) {
                $row_assessment = $result_assessment->fetch_assoc();
                $assessment_id = $row_assessment['assesment_id'];
            } else {
                // Insert a new assessment
                $sql_insert_assessment = "INSERT INTO tblassesment (assesment_id, class_id) VALUES (?, ?)";
                $stmt_insert_assessment = $connection->prepare($sql_insert_assessment);
                $stmt_insert_assessment->bind_param("ss", $assessment_id, $class_id);
                $stmt_insert_assessment->execute();

                // Retrieve the newly inserted assessment_id
                $assessment_id = $stmt_insert_assessment->insert_id;
            }

            // Check if the score already exists
            $sql_existing_score = "SELECT * FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
            $stmt_existing_score = $connection->prepare($sql_existing_score);
            $stmt_existing_score->bind_param("ss", $student_id, $assessment_id);
            $stmt_existing_score->execute();
            $result_existing_score = $stmt_existing_score->get_result();

            if ($result_existing_score->num_rows > 0) {
                // Update the existing score
                $sql_update_score = "UPDATE tblstdassesment SET score = ? WHERE student_id = ? AND assesment_id = ?";
                $stmt_update_score = $connection->prepare($sql_update_score);
                $stmt_update_score->bind_param("sss", $score, $student_id, $assessment_id);
                $stmt_update_score->execute();
            } else {
                // Insert a new score
                $sql_insert_score = "INSERT INTO tblstdassesment (assesment_id, student_id, score, class_id) VALUES (?, ?, ?, ?)";
                $stmt_insert_score = $connection->prepare($sql_insert_score);
                $stmt_insert_score->bind_param("ssss", $assessment_id, $student_id, $score, $class_id);
                $stmt_insert_score->execute();
            }
        }
    }
}

// Add the class_id and user_id as query parameters to the Add Assessment link
$addAssessmentLink = "addassesment.php?class_id=$class_id&user_id=$user_id";
$addStudentLink = "addstudent.php?class_id=$class_id&user_id=$user_id";
$addAttendance = "attendance.php?class_id=$class_id&user_id=$user_id";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
         * {
         font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            text-align: center;
        }

        table {
            width: 70%; /* Adjust the width as needed */
            margin: 20px auto; /* Center the table */
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
        }

        input[type="text"] {
            width: 100%; /* Adjust the width as needed */
            text-align: center;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- Sidebar included -->
            <?php include 'sidebar.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <!-- Navbar included -->
                <?php include 'navbar.php'; ?>

                <!-- Your content goes here -->

                <!-- Add Student and Add Assessment Buttons -->
                <div class="container mt-4">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end mt-5">
                            <a href="<?php echo $addStudentLink; ?>" class="btn btn-primary ms-2">Add Student</a>
                            <!-- Use the modified link for Add Assessment -->
                            <a href="<?php echo $addAssessmentLink; ?>" class="btn btn-primary ms-2">Add Assessment</a>
                            <!-- Added 'ms-2' class for margin-left -->
                            <a href="<?php echo $addAttendance; ?>" class="btn btn-primary ms-2">Attendance</a>
                        </div>
                    </div>
                </div>

                <!-- Centered Table with Score Input Form -->
                <div class="container">
                    <form action="" method="post">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <table>
                            <!-- Your table headers here -->
                            <tr>
                                <th>Student ID</th>
                                <th>Names</th>
                                <?php
                                // Fetch all assessments
                                $sql_assessments = "SELECT DISTINCT * FROM tblassesment WHERE class_id = ?";
                                $stmt_assessments = $connection->prepare($sql_assessments);
                                $stmt_assessments->bind_param("s", $class_id);
                                $stmt_assessments->execute();
                                $result_assessments = $stmt_assessments->get_result();

                                // Output assessment titles as table headers
                                foreach ($result_assessments as $row_assessment) {
                                    echo "<th>" . $row_assessment["assesment_title"] . "</th>";
                                }
                                ?>
                            </tr>

                            <?php
                            // Fetch all students
                            $sql_students = "SELECT * FROM tblstudents WHERE class_id = ?";
                            $stmt_students = $connection->prepare($sql_students);
                            $stmt_students->bind_param("s", $class_id);
                            $stmt_students->execute();
                            $result_students = $stmt_students->get_result();

                            // Fetch existing scores
                            $existingScores = [];
                            $sql_existing_scores = "SELECT tblstudents.student_id, tblassesment.assesment_id, tblstdassesment.score
                                                   FROM tblstdassesment
                                                   INNER JOIN tblstudents ON tblstdassesment.student_id = tblstudents.student_id
                                                   INNER JOIN tblassesment ON tblstdassesment.assesment_id = tblassesment.assesment_id
                                                   WHERE tblstudents.class_id = ?";
                            $stmt_existing_scores = $connection->prepare($sql_existing_scores);
                            $stmt_existing_scores->bind_param("s", $class_id);
                            $stmt_existing_scores->execute();
                            $result_existing_scores = $stmt_existing_scores->get_result();

                            while ($row = $result_existing_scores->fetch_assoc()) {
                                $existingScores[$row['student_id']][$row['assesment_id']] = $row['score'];
                            }

                            // Output table rows with student names
                            while ($row_student = $result_students->fetch_assoc()) {
                                echo "<tr><td>" . $row_student["student_id"] . "</td><td>" . $row_student["fname"] . " " . $row_student["lastname"] . "</td>";

                                // Output a cell for each assessment
                                $result_assessments->data_seek(0); // Reset result set pointer
                                while ($row_assessment = $result_assessments->fetch_assoc()) {
                                    $student_id = $row_student["student_id"];
                                    $assessment_id = $row_assessment["assesment_id"];
                                    echo "<td>";
                                    // Input field for score with corresponding student, assessment, and class IDs
                                    $scoreValue = isset($existingScores[$student_id][$assessment_id]) ? $existingScores[$student_id][$assessment_id] : '';

                                    echo "<input type='text' name='scores[$student_id][$assessment_id]' value='$scoreValue' />";
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </table>
                        <!-- Save button -->
                        <button type="submit" class="btn btn-primary ms-2">SAVE SCORES</button>
                    </form>
                </div>
                <!-- Rest of your content -->
            </main>
        </div>
    </div>
</body>

</html>
