                <?php
                require_once "config.php";
                include 'sidebar.php';
                include 'navbar.php';

                // Fetch the class_id
                $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

                // Fetch student data from the database based on the class_id
                $sql_students = "SELECT * FROM tblstudents WHERE class_id = ?";
                $stmt_students = $connection->prepare($sql_students);
                $stmt_students->bind_param("s", $class_id);
                $stmt_students->execute();

                // Store student data in an array
                $students = array();
                $result_students = $stmt_students->get_result();
                if ($result_students->num_rows > 0) {
                    while ($row_student = $result_students->fetch_assoc()) {
                        $students[] = $row_student;
                    }
                }

                ?>

<!DOCTYPE html>
<html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Average Score</title>
                    <style>
                        body {
                            display: flex;
                            height: 100vh;        
                            background-color: #f4f4f4;
                            font-family: Arial, sans-serif;
                        }

                        .table-container {
                            position: relative;
                            margin-top: 50px;
                        }

                        .average-container {
                            text-align: center;
                            padding: 20px;
                            background-color: #fff;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            margin-bottom: 20px;
                        }

                        table {
                            border-collapse: collapse;
                            margin-top: 50px;
                            margin-left: 220px;
                        }

                        th, td {
                            border: 1px solid black;
                            padding: 8px;
                            text-align: center;
                        }

                        th {
                            background-color: #f2f2f2;
                        }

                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }

                        tr:hover {
                            background-color: #f1f1f1;
                        }

                        .print-button {
                            position: absolute;
                            top: 20px;
                            left:250px;
                        }

                        /* Automatic width adjustment for Name column */
                        .name-column {
                            white-space: nowrap; /* Prevent line breaks within names */
                        }
                        .student-id-column {
                        white-space: nowrap; /* Prevent line breaks within student IDs */
                    }
                    </style>
            </head>
            <body>
            <div class="table-container">
            <div class="average-container">
                <?php
                // SQL query to calculate average score for Summative, Quiz, and Exam assessments for MidTerm
                $sql_combined_midterm = "
                SELECT
                    tsa.student_id,
                    ts.class_id,
                    AVG(CASE WHEN ts.assesment_type = 'Summative' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS summative_average,
                
                    AVG(CASE WHEN ts.assesment_type = 'Exam' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS exam_average,
                    AVG(CASE WHEN ts.assesment_type = 'Output' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS output_average,
                    AVG(CASE WHEN ts.assesment_type = 'Laboratory' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS laboratory_average,
                    AVG(CASE WHEN ts.assesment_type = 'Assignment' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS assignment_average,
                    AVG(CASE WHEN ts.assesment_type = 'Participation' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS participation_average,
                    AVG(CASE WHEN ts.assesment_type = 'Behavior' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS behavior_average,
                    s.fname,
                    s.lastname,
                    s.middlename
                FROM
                    tblstdassesment tsa
                JOIN
                    tblassesment ts ON tsa.class_id = ts.class_id AND tsa.assesment_id = ts.assesment_id
                JOIN
                    tblstudents s ON tsa.student_id = s.student_id
                WHERE
                    ts.assesment_type IN ('Summative',  'Exam', 'Output', 'Laboratory', 'Assignment', 'Participation', 'Behavior')
                    AND ts.term = 'MidTerm'
                    AND ts.class_id = ? 
                GROUP BY
                    tsa.student_id,
                    ts.class_id
                  ORDER BY
                s.lastname ASC";

                $stmt_combined_midterm = $connection->prepare($sql_combined_midterm);
                $stmt_combined_midterm->bind_param("s", $class_id);  // Bind the parameter
                $stmt_combined_midterm->execute();
                $result_combined_midterm = $stmt_combined_midterm->get_result();

                // SQL query to calculate average score for Summative, Quiz, and Exam assessments for FinalTerm
                $sql_combined_finalterm = "
                SELECT
                    tsa.student_id,
                    ts.class_id,
                    AVG(CASE WHEN ts.assesment_type = 'Summative' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS summative_average,
                
                    AVG(CASE WHEN ts.assesment_type = 'Exam' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS exam_average,
                    AVG(CASE WHEN ts.assesment_type = 'Output' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS output_average,
                    AVG(CASE WHEN ts.assesment_type = 'Laboratory' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS laboratory_average,
                    AVG(CASE WHEN ts.assesment_type = 'Assignment' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS assignment_average,
                    AVG(CASE WHEN ts.assesment_type = 'Participation' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS participation_average,
                    AVG(CASE WHEN ts.assesment_type = 'Behavior' THEN tsa.score / ts.total_item * 100 ELSE NULL END) AS behavior_average,
                    s.fname,
                    s.lastname,
                    s.middlename
                FROM
                    tblstdassesment tsa
                JOIN
                    tblassesment ts ON tsa.class_id = ts.class_id AND tsa.assesment_id = ts.assesment_id
                JOIN
                    tblstudents s ON tsa.student_id = s.student_id
                WHERE
                    ts.assesment_type IN ('Summative',  'Exam', 'Output', 'Laboratory', 'Assignment', 'Participation', 'Behavior')
                    AND ts.term = 'Final'
                    AND ts.class_id = ? 
                GROUP BY
                    tsa.student_id,
                    ts.class_id
                    ORDER BY
                    s.lastname ASC";

                $stmt_combined_finalterm = $connection->prepare($sql_combined_finalterm);
                $stmt_combined_finalterm->bind_param("s", $class_id);  // Bind the parameter
                $stmt_combined_finalterm->execute();
                $result_combined_finalterm = $stmt_combined_finalterm->get_result();

                // Check for errors
                if ($result_combined_finalterm === false) {
                    die("Error executing the FinalTerm query: " . $connection->error);
                }
                // Check if there are rows in the Midterm result
                if ($result_combined_midterm->num_rows > 0) {
                    ?>
                    <table>
                    <tr>
                        <th colspan="20"  style="background-color: #f593f0;">Class ID: <?php echo $class_id; ?></th>
                    </tr>
                    <tr>
                        <th rowspan="2" style="background-color: #66d0fa;">Student Id</th>
                        <th rowspan="2" class="name-column" style="background-color: #66d0fa;">Name </th>
                        <th colspan="7" style="background-color: #5cf7b9">Midterm </th>
                        <th rowspan="2" style="background-color: #5cf7b9">Midterm Average</th>
                        <th colspan="7" style="background-color: #fcaecb;">FinalTerm</th>
                        <th rowspan="2" style="background-color: #fcaecb;">FinalTerm Average</th>
                        <th rowspan="2" style="background-color: #5ff54e;">General Average</th>
                    </tr>
                    <tr>
                        <th style="background-color: #5cf7b9">Summative Average</th>
                    
                        <th style="background-color: #5cf7b9">Exam Average</th>
                        <th style="background-color: #5cf7b9">Output Average</th>
                        <th style="background-color: #5cf7b9">Laboratory Average</th>
                        <th style="background-color: #5cf7b9">Assignment Average</th>
                        <th style="background-color: #5cf7b9">Participation Average</th>
                        <th style="background-color: #5cf7b9">Behavior Average</th>

                        <th style="background-color: #fcaecb;">Summative Average</th>
                    
                        <th style="background-color: #fcaecb;">Exam Average</th>
                        <th style="background-color: #fcaecb;">Output Average</th>
                        <th style="background-color: #fcaecb;">Laboratory Average</th>
                        <th style="background-color: #fcaecb;">Assignment Average</th>
                        <th style="background-color: #fcaecb;">Participation Average</th>
                        <th style="background-color: #fcaecb;">Behavior Average</th>
                    </tr>
                                                <?php
                            $knowledge_weight = 0.4;
                            $skills_weight = 0.5;
                            $attitude_weight = 0.1;

                            $studentCounter = 1; // Initialize the counter

                            // Display the Midterm and FinalTerm results
                            while ($row_combined_midterm = $result_combined_midterm->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='student-id-column'>" . $row_combined_midterm['student_id'] . "</td>";
                                echo "<td class='name-column' style='text-align: left;'>" . $studentCounter . ".) " . $row_combined_midterm['lastname'] . ' ' . $row_combined_midterm['fname'] . ' '. $row_combined_midterm['middlename'] . ". </td>";
                                echo "<td>" . ($row_combined_midterm['summative_average'] !== null ? $row_combined_midterm['summative_average'] . '%' : '') . "</td>";         
                                echo "<td>" . ($row_combined_midterm['exam_average'] !== null ? $row_combined_midterm['exam_average'] . '%' : '') . "</td>";
                                echo "<td>" . ($row_combined_midterm['output_average'] !== null ? $row_combined_midterm['output_average'] . '%' : '') . "</td>";
                                echo "<td>" . ($row_combined_midterm['laboratory_average'] !== null ? $row_combined_midterm['laboratory_average'] . '%' : '') . "</td>";
                                echo "<td>" . ($row_combined_midterm['assignment_average'] !== null ? $row_combined_midterm['assignment_average'] . '%' : '') . "</td>";
                                echo "<td>" . ($row_combined_midterm['participation_average'] !== null ? $row_combined_midterm['participation_average'] . '%' : '') . "</td>";
                                echo "<td>" . ($row_combined_midterm['behavior_average'] !== null ? $row_combined_midterm['behavior_average'] . '%' : '') . "</td>";
                                $studentCounter++;
                                
                                // Calculate general average for Midterm
                                $midterm_general_average =
                                    $knowledge_weight * (0.6 * $row_combined_midterm['exam_average'] + 0.4 * $row_combined_midterm['summative_average']) +
                                    $skills_weight * (0.4 * $row_combined_midterm['output_average'] + 0.5 * $row_combined_midterm['laboratory_average'] + 0.1 * $row_combined_midterm['assignment_average']) +
                                    $attitude_weight * (0.5 * $row_combined_midterm['participation_average'] + 0.5 * $row_combined_midterm['behavior_average']);

                                echo "<td >" . number_format($midterm_general_average, 2) . '%</td>';

                                // Initialize $finalterm_general_average
                                $finalterm_general_average = 0;

                                // Fetch and display the FinalTerm results
                                $row_combined_finalterm = $result_combined_finalterm->fetch_assoc();

                                // Check if the FinalTerm result exists
                                if ($row_combined_finalterm) {
                                    echo "<td>" . ($row_combined_finalterm['summative_average'] !== null ? $row_combined_finalterm['summative_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['exam_average'] !== null ? $row_combined_finalterm['exam_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['output_average'] !== null ? $row_combined_finalterm['output_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['laboratory_average'] !== null ? $row_combined_finalterm['laboratory_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['assignment_average'] !== null ? $row_combined_finalterm['assignment_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['participation_average'] !== null ? $row_combined_finalterm['participation_average'] . '%' : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['behavior_average'] !== null ? $row_combined_finalterm['behavior_average'] . '%' : '') . "</td>";
                                
                                    // Calculate general average for FinalTerm
                                    $finalterm_general_average =
                                        $knowledge_weight * (0.6 * $row_combined_finalterm['exam_average'] + 0.4 * $row_combined_finalterm['summative_average']) +
                                        $skills_weight * (0.4 * $row_combined_finalterm['output_average'] + 0.5 * $row_combined_finalterm['laboratory_average'] + 0.1 * $row_combined_finalterm['assignment_average']) +
                                        $attitude_weight * (0.5 * $row_combined_finalterm['participation_average'] + 0.5 * $row_combined_finalterm['behavior_average']);

                                    echo "<td>" . number_format($finalterm_general_average, 2) . '%</td>';
                                } else {
                                    // If FinalTerm result doesn't exist, display N/A for all FinalTerm columns
                                    for ($i = 0; $i < 7; $i++) {
                                        echo "<td></td>";
                                    }

                                    // Display N/A for General Average for FinalTerm
                                    echo "<td></td>";  
                                }

                                // Calculate and display general average based on weights
                                $general_average =
                                    0.4 * $midterm_general_average +
                                    0.6 * $finalterm_general_average;

                                echo "<td>" . number_format($general_average, 2) . '%</td>';

                                echo "</tr>";
                            }
                            ?>
                            </table>
                            <?php // Display black portion for general average if there is no FinalTerm data
                            if (!$row_combined_finalterm) {
                                // Display N/A for all FinalTerm columns
                                echo "<tr>";
                                echo "<td colspan='9'></td>";
                                // Display black portion for General Average for FinalTerm
                                echo "<td></td>";  
                                echo "</tr>";
                            }
                            ?>


                    <?php
                } else {
                    // If no records are found, display an empty table with a message
                    ?>
                    <table>
                        <tr>
                            <th rowspan="2">Student ID</th>
                            <th rowspan="2" class="name-column">Name</th>
                            <th colspan="8">Midterm</th>
                            <th colspan="8">FinalTerm</th>
                        </tr>
                        <tr>
                            <!-- Your existing table header cells here -->
                        </tr>
                        <tr>
                            <td colspan="17">No records found.</td>
                        </tr>
                    </table>
                    <?php
                }
                ?>
            </div>
            <!-- Add a button to trigger printing -->
            <button onclick="printData()" class="print-button">Print Data</button>
            </div>

            <script>
                function printData() {
                    // Open a new window for printing
                    var printWindow = window.open('', '_blank');
                    printWindow.document.write('<html><head><title>Print</title>');
                    printWindow.document.write('<style>');
                    printWindow.document.write('table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; text-align: center; }');
                    printWindow.document.write('th { background-color: #f2f2f2; }');
                    printWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
                    printWindow.document.write('tr:hover { background-color: #f1f1f1; }');
                    printWindow.document.write('.name-column { white-space: nowrap; }'); // Add style for name-column
                    printWindow.document.write('</style>');
                    printWindow.document.write('</head><body>');
                    
                    // Append the content of the average-container div to the new window
                    printWindow.document.write(document.querySelector('.average-container').innerHTML);
                    
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    
                    // Trigger the print dialog
                    printWindow.print();
                }
            </script>

            </body>
</html>
