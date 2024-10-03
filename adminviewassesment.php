<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Establish a connection to the database
$result = mysqli_connect('localhost', 'root', '', 'classrecord');

// Check if the connection was successful
if (!$result) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if class_id is provided in the URL
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Query to fetch assessments for the specific class
    $assessmentsQuery = "SELECT * FROM tblassesment WHERE class_id = '$class_id'";
    $assessmentsResult = mysqli_query($result, $assessmentsQuery);
} else {
    // Redirect to a page with an error message if class_id is not provided
    header("Location: error.php?message=Class ID not provided");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assessments</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 56px; /* Adjust body padding to accommodate fixed navbar */
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        table {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }

        td a {
            color: #007bff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <?php include 'adminsidebar.php'; ?>
    <?php include 'adminnavbar.php'; ?>

    <!-- Wrap assessment information in a container -->
    <div class="container">
        <h1>Assessments Data</h1>
        
        <!-- Display assessment information in a table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">#</th> <!-- Counter column -->
                        <th onclick="sortTable(1)">Assessment Name</th>
                        <th onclick="sortTable(2)">Total Item</th>
                        <th onclick="sortTable(3)">Term</th>
                        <th onclick="sortTable(4)">Class ID</th>
                        <!-- Add more table headers if needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    while ($assessment = mysqli_fetch_assoc($assessmentsResult)) : ?>
                        <tr>
                            <td><?php echo $counter++; ?>.</td>                          
                            <td><?php echo $assessment['assesment_title']; ?></td>
                            <td><?php echo $assessment['total_item']; ?></td>
                            <td><?php echo $assessment['term']; ?></td>
                            <td><?php echo $assessment['class_id']; ?></td>
                            <!-- Add more table cells if needed -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector("table");
            switching = true;
            // Set the sorting direction to ascending:
            dir = "asc";
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                    one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    /* Check if the two rows should switch place,
                    based on the direction, asc or desc: */
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    // Each time a switch is done, increase this count by 1:
                    switchcount ++;
                } else {
                    /* If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again. */
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

<?php
// Close the database connection
mysqli_close($result);
?>