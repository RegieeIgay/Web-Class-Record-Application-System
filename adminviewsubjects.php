<?php
session_start();
include 'adminsidebar.php';
include 'adminnavbar.php';

// Assuming you have a mechanism to determine the current user_id
// For example, retrieving it from the session or any other source
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'classrecord';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify the SQL query to select subjects for the specific user_id and sort by user_id
$sql = "SELECT * FROM tblsubjects";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 90%;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            cursor: pointer; /* Add cursor pointer for sorting */
        }

        th {
            background-color: #f2f2f2;
        }

        td:hover {
            background-color: #f0f0f0;
        }

        td a {
            color: #3498db;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Subject Data</h1>
    <table id="subjectTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Course Number</th>
                <th onclick="sortTable(1)">Title</th>
                <th onclick="sortTable(2)">Units</th>
                <th onclick="sortTable(2)">Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Assuming you have fetched the data and stored it in $result
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['course_number'] . '</td>';
                echo '<td>' . $row['descriptive_title'] . '</td>';
                echo '<td>' . $row['credit_units'] . '</td>';
                echo '<td>' . $row['course_description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function sortTable(columnIndex) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("subjectTable");
    switching = true;
    dir = "asc"; // Set the sorting direction to ascending initially
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[columnIndex];
            y = rows[i + 1].getElementsByTagName("td")[columnIndex];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
</script>

</body>
</html>