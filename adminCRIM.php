<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Class Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 90%;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .filter-section {
            max-width: 90%;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            margin-top: 50px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-section label, .filter-section select {
            margin: 5px; /* Adjusted margin */
            display: inline-block;
            width: auto; /* Ensures dropdowns adjust to content */
            font-size: 14px; /* Reduced font size */
        }
    </style>
</head>
<body>

<?php
session_start();
include 'adminsidebar.php';
include 'adminnavbar.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tblclass WHERE class_course='BSCRIM'";
$result = $conn->query($sql);
?>

<div class="filter-section">
    <label for="yearFilter">Year:</label>
    <select id="yearFilter" class="form-select">
        <option value="">Select Year</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>

    <label for="sectionFilter">Section:</label>
    <select id="sectionFilter" class="form-select">
        <option value="">Select Section</option>
        <option value="A">A</option>
        <option value="B">B</option>
    </select>

    <label for="schoolYearFilter">School Year:</label>
    <select id="schoolYearFilter" class="form-select">
        <option value="">Select School Year</option>
        <?php
            // Generate school years from 2010 to 2050
            $currentYear = date("Y");
            for ($year = 2010; $year <= 2050; $year++) {
                $nextYear = $year + 1;
                echo "<option value='$year-$nextYear'>$year-$nextYear</option>";
            }
        ?>
    </select>
</div>

<div class="container">
    <h1>BACHELOR OF SCIENCE IN CRIMINAL JUSTICE</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Course Number</th>
                <th>Class Course</th>
                <th>Class Year</th>
                <th>Class Section</th>
                <th>School Year</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['course_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['class_course']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['class_year']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['class_section']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['school_year']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found.</td></tr>";
            }

            // Close database connection
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var filters = ["yearFilter", "sectionFilter", "schoolYearFilter"];
    filters.forEach(function(filter) {
        document.getElementById(filter).addEventListener("change", function() {
            var year = document.getElementById('yearFilter').value.toLowerCase();
            var section = document.getElementById('sectionFilter').value.toLowerCase();
            var schoolYear = document.getElementById('schoolYearFilter').value.toLowerCase();

            var rows = document.querySelectorAll("table tbody tr");

            rows.forEach(function(row) {
                var yearText = row.cells[2].textContent.toLowerCase();
                var sectionText = row.cells[3].textContent.toLowerCase();
                var schoolYearText = row.cells[4].textContent.toLowerCase();

                var showRow = (year === "" || yearText === year) &&
                              (section === "" || sectionText === section) &&
                              (schoolYear === "" || schoolYearText === schoolYear);

                row.style.display = showRow ? "" : "none";
            });
        });
    });
});
</script>

</body>
</html>