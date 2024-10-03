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
                if ($result_students->num_rows >    0) {
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
    <title>CLASSRECORD</title>
    <style>
        /* CSS styles for the page */
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
            left: 250px;
        }

        /* Automatic width adjustment for Name and Student ID columns to prevent line breaks */
        .name-column,
        .student-id-column {
            white-space: nowrap;
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

                    COALESCE(AVG(CASE WHEN ts.assesment_type = 'Summative' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 40
                         ELSE (tsa.score / ts.total_item * 100) * 0.4 
                    END 
                END), NULL) AS summative_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Exam' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 60
                         ELSE (tsa.score / ts.total_item * 100) * 0.6 
                    END 
                END), NULL) AS exam_average,


                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Output' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 40
                         ELSE (tsa.score / ts.total_item * 100) * 0.4 
                    END 
                END), NULL) AS output_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Participation' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 30
                         ELSE (tsa.score / ts.total_item * 100) * 0.3 
                    END 
                END), NULL) AS participation_average,

                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Activity' THEN 
                CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 20
                     ELSE (tsa.score / ts.total_item * 100) * 0.2
                END 
            END), NULL) AS activity_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Assignment' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 10
                         ELSE (tsa.score / ts.total_item * 100) * 0.1 
                    END 
                END), NULL) AS assignment_average,
                
                  

                    AVG(CASE WHEN ts.assesment_type = 'Engagement' THEN (tsa.score / ts.total_item * 100) * 0.5 ELSE NULL END) AS engagement_average,          
                    AVG(CASE WHEN ts.assesment_type = 'Behavior' THEN (tsa.score / ts.total_item * 100) * 0.5 ELSE NULL END) AS behavior_average, 
            
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
                    ts.assesment_type IN ('Summative',  'Exam', 'Output', 'Participation', 'Activity', 'Assignment', 'Engagement', 'Behavior')
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
                
                    COALESCE(AVG(CASE WHEN ts.assesment_type = 'Summative' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 40
                         ELSE (tsa.score / ts.total_item * 100) * 0.4 
                    END 
                END), NULL) AS summative_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Exam' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 60
                         ELSE (tsa.score / ts.total_item * 100) * 0.6 
                    END 
                END), NULL) AS exam_average,


                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Output' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 40
                         ELSE (tsa.score / ts.total_item * 100) * 0.4 
                    END 
                END), NULL) AS output_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Participation' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 30
                         ELSE (tsa.score / ts.total_item * 100) * 0.3 
                    END 
                END), NULL) AS participation_average,

                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Activity' THEN 
                CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 20
                     ELSE (tsa.score / ts.total_item * 100) * 0.2
                END 
            END), NULL) AS activity_average,
                
                COALESCE(AVG(CASE WHEN ts.assesment_type = 'Assignment' THEN 
                    CASE WHEN COALESCE(tsa.score, 0) = 0 THEN (25 / 100) * 10
                         ELSE (tsa.score / ts.total_item * 100) * 0.1 
                    END 
                END), NULL) AS assignment_average,
                
                    AVG(CASE WHEN ts.assesment_type = 'Engagement' THEN (tsa.score / ts.total_item * 100) * 0.5 ELSE NULL END) AS engagement_average,          
                    AVG(CASE WHEN ts.assesment_type = 'Behavior' THEN (tsa.score / ts.total_item * 100) * 0.5 ELSE NULL END) AS behavior_average, 
            
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
                    ts.assesment_type IN ('Summative',  'Exam', 'Output', 'Participation', 'Activity', 'Assignment', 'Engagement', 'Behavior')
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
                        <th colspan="51"  style="background-color: #f593f0;">Class ID: <?php echo $class_id; ?></th>
                    </tr>
                    <tr>
                        <th rowspan="3" style="background-color: #66d0fa;">Student Id</th>
                        <th rowspan="3" class="name-column" style="background-color: #66d0fa;">Name </th>
                        <th colspan="22" style="background-color: #5cf7b9">Midterm (40)</th>
                        <th rowspan="3" style="background-color: #5cf7b9">Midterm Average (40)</th>
                        <th rowspan="3" style="background-color: #5cf7b9">Midterm Equivalent </th>
                        <th colspan="22" style="background-color: #fcaecb;">FinalTerm (60)</th>
                        <th rowspan="3" style="background-color: #fcaecb;">FinalTerm Average</th>
                          <th rowspan="3" style="background-color: #fcaecb">Finalterm Equivalent </th>
                        <th rowspan="3" style="background-color: #5ff54e;">General Average (100)</th>
                    </tr>
                    
                    <tr>
                        <th colspan="4" style="background-color: #5cf7b9">Knowledge </th>
                        <th rowspan="2" style="background-color: #5cf7b9">Knowledge Average (50)</th>
                        <th rowspan="2" style="background-color: #5cf7b9">Knowledge Equivalent</th>
                        <th colspan="8" style="background-color: #5cf7b9">Skills </th>
                        <th rowspan="2" style="background-color: #5cf7b9">Skills Average (40)</th>
                        <th rowspan="2" style="background-color: #5cf7b9">Skills Equivalent</th>
                        <th colspan="4" style="background-color: #5cf7b9">Attitude </th>
                        <th rowspan="2" style="background-color: #5cf7b9">Attitude Average (10)</th>
                        <th rowspan="2" style="background-color: #5cf7b9">Attitude Equivalent</th>
                        
                        <th colspan="4" style="background-color: #fcaecb">Knowledge </th>
                        <th rowspan="2" style="background-color: #fcaecb">Knowledge Average (50)</th>
                        <th rowspan="2" style="background-color: #fcaecb">Knowledge Equivalent</th>
                        <th colspan="8" style="background-color: #fcaecb">Skills </th>
                        <th rowspan="2" style="background-color: #fcaecb">Skills Average (40)</th>
                        <th rowspan="2" style="background-color: #fcaecb">Skills Equivalent</th>
                        <th colspan="4" style="background-color: #fcaecb">Attitude </th>
                        <th rowspan="2" style="background-color: #fcaecb">Attitude Average (10)</th>
                        <th rowspan="2" style="background-color: #fcaecb">Attitude Equivalent</th>
                    </tr>
                    <tr>
                        <th style="background-color: #5cf7b9">Summative Average (40)</th>
                        <th style="background-color: #5cf7b9">Summative Equivalent</th>
                        <th style="background-color: #5cf7b9">Exam Average (60)</th>
                        <th style="background-color: #5cf7b9">Exam Equivalent</th>
                        <th style="background-color: #5cf7b9">Output Average(40)</th>
                        <th style="background-color: #5cf7b9">Output Equivalent </th>
                        <th style="background-color: #5cf7b9">Participation Average(30)</th>
                        <th style="background-color: #5cf7b9">Participation Equivalent</th>
                        <th style="background-color: #5cf7b9">Activity Average(10)</th>
                        <th style="background-color: #5cf7b9">Activity Equivalent</th>
                        <th style="background-color: #5cf7b9">Assignment Average(10)</th>
                        <th style="background-color: #5cf7b9">Assignment Equivalent</th>
                        <th style="background-color: #5cf7b9">Engagement Average (50)</th>
                        <th style="background-color: #5cf7b9">Engagement Equivalent</th>
                        <th style="background-color: #5cf7b9">Behavior Average (50)</th>
                        <th style="background-color: #5cf7b9">Behavior Equivalent</th>
 
                        <th style="background-color: #fcaecb">Summative Average (40)</th>
                        <th style="background-color: #fcaecb">Summative Equivalent</th>
                        <th style="background-color: #fcaecb">Exam Average (60)</th>
                        <th style="background-color: #fcaecb">Exam Equivalent</th>
                        <th style="background-color: #fcaecb">Output Average(40)</th>
                        <th style="background-color: #fcaecb">Output Equivalent </th>
                        <th style="background-color: #fcaecb">Participation Average(50)</th>
                        <th style="background-color: #fcaecb">Participation Equivalent</th>
                        <th style="background-color: #fcaecb">Activity Average(10)</th>
                        <th style="background-color: #fcaecb">Activity Equivalent</th>
                        <th style="background-color: #fcaecb">Assignment Average(10)</th>
                        <th style="background-color: #fcaecb">Assignment Equivalent</th>
                        <th style="background-color: #fcaecb">Engagement Average (50)</th>
                        <th style="background-color: #fcaecb">Engagement Equivalent</th>
                        <th style="background-color: #fcaecb">Behavior Average (50)</th>
                        <th style="background-color: #fcaecb">Behavior Equivalent</th>
                    </tr>
                                                <?php
                         $knowledge_weight = 0.4;
                         $skills_weight = 0.5;
                         $attitude_weight = 0.1;
                         
                         $studentCounter = 1; // Initialize the counter
                         
                         // Display the Midterm results
                         while ($row_combined_midterm = $result_combined_midterm->fetch_assoc()) {
                             echo "<tr>";
                             echo "<td class='student-id-column'>" . $row_combined_midterm['student_id'] . "</td>";
                             echo "<td class='name-column' style='text-align: left;'>" . $studentCounter . ".) " . $row_combined_midterm['lastname'] . ' ' . $row_combined_midterm['fname'] . ' '. $row_combined_midterm['middlename'] . ". </td>";
                         
                             // Display Summative average and percentage
                             echo "<td>" . ($row_combined_midterm['summative_average'] !== null ? number_format($row_combined_midterm['summative_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['summative_average'] !== null ? number_format(($row_combined_midterm['summative_average'] / 40) * 100, 2) : '') . "</td>";
                         
                             // Display Exam average and percentage
                             echo "<td>" . ($row_combined_midterm['exam_average'] !== null ? number_format($row_combined_midterm['exam_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['exam_average'] !== null ? number_format(($row_combined_midterm['exam_average'] / 60) * 100, 2) : '') . "</td>";
                         
                             // Calculate and display Knowledge average and percentage
                             $midterm_knowledge_average = (0.4 * ($row_combined_midterm['summative_average'] / 40 * 100)) + (0.6 * ($row_combined_midterm['exam_average'] / 60 * 100));
                             $midterm_knowledge_average_scaled = ($midterm_knowledge_average / 100) * 50;
                             echo "<td>" . number_format($midterm_knowledge_average_scaled, 2) . '</td>';
                             echo "<td>" . number_format(($midterm_knowledge_average_scaled / 50) * 100, 2) . '</td>';
                         
                             // Display Output average and percentage
                             echo "<td>" . ($row_combined_midterm['output_average'] !== null ? number_format($row_combined_midterm['output_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['output_average'] !== null ? number_format(($row_combined_midterm['output_average'] / 40) * 100, 2) : '') . "</td>";
                         
                             // Display Participation average and percentage
                             echo "<td>" . ($row_combined_midterm['participation_average'] !== null ? number_format($row_combined_midterm['participation_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['participation_average'] !== null ? number_format(($row_combined_midterm['participation_average'] / 30) * 100, 2) : '') . "</td>";
                         

                              // Display Assignment average and percentage
                             echo "<td>" . ($row_combined_midterm['activity_average'] !== null ? number_format($row_combined_midterm['activity_average'],2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['activity_average'] !== null ? number_format(($row_combined_midterm['activity_average'] / 20) * 100, 2) : '') . "</td>";

                             // Display Assignment average and percentage
                             echo "<td>" . ($row_combined_midterm['assignment_average'] !== null ? number_format($row_combined_midterm['assignment_average'],2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['assignment_average'] !== null ? number_format(($row_combined_midterm['assignment_average'] / 10) * 100, 2) : '') . "</td>";


                             // Calculate and display Skills average and percentage
                             $midterm_skills_average = (0.4 * ($row_combined_midterm['output_average'] / 40 * 100)) + (0.3 * ($row_combined_midterm['participation_average'] / 30 * 100))  + (0.2 * ($row_combined_midterm['activity_average'] / 20 * 100))  + (0.1 * ($row_combined_midterm['assignment_average'] / 10 * 100));
                             $midterm_skills_average_scaled = ($midterm_skills_average / 100) * 40;
                             echo "<td>" . number_format($midterm_skills_average_scaled, 2) . '</td>';
                             echo "<td>" . number_format(($midterm_skills_average_scaled / 40) * 100, 2) . '</td>';
                         
                             // Display Participation average and percentage
                             echo "<td>" . ($row_combined_midterm['engagement_average'] !== null ? number_format($row_combined_midterm['engagement_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['engagement_average'] !== null ? number_format(($row_combined_midterm['engagement_average'] / 50) * 100, 2) : '') . "</td>";
                         
                             // Display Behavior average and percentage
                             echo "<td>" . ($row_combined_midterm['behavior_average'] !== null ? number_format($row_combined_midterm['behavior_average'], 2) : '') . "</td>";
                             echo "<td>" . ($row_combined_midterm['behavior_average'] !== null ? number_format(($row_combined_midterm['behavior_average'] / 50) * 100, 2) : '') . "</td>";
                         
                             // Calculate and display Attitude average and percentage
                             $midterm_attitude_average = (0.5 * ($row_combined_midterm['engagement_average'] / 50 * 100)) + (0.5 * ($row_combined_midterm['behavior_average'] / 50 * 100));
                             $midterm_attitude_average_scaled = ($midterm_attitude_average / 100) * 10;
                             echo "<td>" . number_format($midterm_attitude_average_scaled, 2) . '</td>';
                             echo "<td>" . number_format(($midterm_attitude_average_scaled / 10) * 100, 2) . '</td>';
                         
                             // Calculate and display Midterm General Average
                            // Calculate the total scaled average
                                    $total_scaled_average = $midterm_knowledge_average_scaled + $midterm_skills_average_scaled + $midterm_attitude_average_scaled;

                                    // Scale the total average to be displayed up to 60
                                    $midterm_general_average_scaled = ($total_scaled_average / 100) * 40;

                                    // Output the scaled general average with two decimal places
                                    echo "<td>" . number_format($midterm_general_average_scaled, 2) . "</td>";
                                    echo "<td>" . number_format(($midterm_general_average_scaled/40)*100, 2) . "</td>";

                         
                            
                         
                             $studentCounter++;
                  
                         
                            

                                // Initialize $finalterm_general_average
                                $finalterm_general_average = 0;

                                // Fetch and display the FinalTerm results
                                $row_combined_finalterm = $result_combined_finalterm->fetch_assoc();

                                // Check if the FinalTerm result exists
                                if ($row_combined_finalterm) {


                                    // Display Summative average and percentage
                                    echo "<td>" . ($row_combined_finalterm['summative_average'] !== null ? number_format($row_combined_finalterm['summative_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['summative_average'] !== null ? number_format(($row_combined_finalterm['summative_average'] / 40) * 100, 2) : '') . "</td>";
                                
                                    // Display Exam average and percentage
                                    echo "<td>" . ($row_combined_finalterm['exam_average'] !== null ? number_format($row_combined_finalterm['exam_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['exam_average'] !== null ? number_format(($row_combined_finalterm['exam_average'] / 60) * 100, 2) : '') . "</td>";
                                        

                                      // Calculate and display Knowledge average and percentage
                                    $final_term_knowledge_average = (0.4 * ($row_combined_finalterm['summative_average'] / 40 * 100)) + (0.6 * ($row_combined_finalterm['exam_average'] / 60 * 100));
                                    $finalterm_knowledge_average_scaled = ($final_term_knowledge_average / 100) * 50;
                                    echo "<td>" . number_format($finalterm_knowledge_average_scaled, 2) . '</td>';
                                    echo "<td>" . number_format(($finalterm_knowledge_average_scaled / 50) * 100, 2) . '</td>';

                                   
                                    // Display Output average and percentage
                                    echo "<td>" . ($row_combined_finalterm['output_average'] !== null ? number_format($row_combined_finalterm['output_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['output_average'] !== null ? number_format(($row_combined_finalterm['output_average'] / 40) * 100, 2) : '') . "</td>";

                                    // Display Participation average and percentage
                                    echo "<td>" . ($row_combined_finalterm['participation_average'] !== null ? number_format($row_combined_finalterm['participation_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['participation_average'] !== null ? number_format(($row_combined_finalterm['participation_average'] / 30) * 100, 2) : '') . "</td>";


                                     // Display Laboratory average and percentage
                                    echo "<td>" . ($row_combined_finalterm['activity_average'] !== null ? number_format($row_combined_finalterm['activity_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['activity_average'] !== null ? number_format(($row_combined_finalterm['activity_average'] / 20) * 100, 2) : '') . "</td>";

                                    // Display Assignment average and percentage
                                    echo "<td>" . ($row_combined_finalterm['assignment_average'] !== null ? number_format($row_combined_finalterm['assignment_average'],2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['assignment_average'] !== null ? number_format(($row_combined_finalterm['assignment_average'] / 10) * 100, 2) : '') . "</td>";

                                   
                                              // Calculate and display Skills average and percentage
                                        $finalterm_skills_average = (0.4 * ($row_combined_finalterm['output_average'] / 40 * 100)) + (0.3 * ($row_combined_finalterm['participation_average'] / 30 * 100)) + (0.2 * ($row_combined_finalterm['activity_average'] / 20 * 100)) + (0.1 * ($row_combined_finalterm['assignment_average'] / 10 * 100));
                                        $finalterm_skills_average_scaled = ($finalterm_skills_average / 100) * 40;
                                        echo "<td>" . number_format($finalterm_skills_average_scaled, 2) . '</td>';
                                        echo "<td>" . number_format(($finalterm_skills_average_scaled / 40) * 100, 2) . '</td>';


                                     // Display Participation average and percentage
                                    echo "<td>" . ($row_combined_finalterm['engagement_average'] !== null ? number_format($row_combined_finalterm['engagement_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['engagement_average'] !== null ? number_format(($row_combined_finalterm['engagement_average'] / 50) * 100, 2) : '') . "</td>";
                                 
                                        // Display Behavior average and percentage
                                    echo "<td>" . ($row_combined_finalterm['behavior_average'] !== null ? number_format($row_combined_finalterm['behavior_average'], 2) : '') . "</td>";
                                    echo "<td>" . ($row_combined_finalterm['behavior_average'] !== null ? number_format(($row_combined_finalterm['behavior_average'] / 50) * 100, 2) : '') . "</td>";
                                
                                        // Calculate and display Attitude average and percentage
                                        $final_term_attitude_average = (0.5 * ($row_combined_finalterm['engagement_average'] / 50 * 100)) + (0.5 * ($row_combined_finalterm['behavior_average'] / 50 * 100));
                                        $finalterm_attitude_average_scaled = ($final_term_attitude_average / 100) * 10;
                                        echo "<td>" . number_format($finalterm_attitude_average_scaled, 2) . '</td>';
                                        echo "<td>" . number_format(($finalterm_attitude_average_scaled / 10) * 100, 2) . '</td>';
                                   
                                          // Calculate the total scaled average
                                    $finaltotal_scaled_average = $finalterm_knowledge_average_scaled + $finalterm_skills_average_scaled + $finalterm_attitude_average_scaled;

                                    // Scale the total average to be displayed up to 60
                                    $finalterm_general_average_scaled = ($finaltotal_scaled_average / 100) * 60;

                                    // Output the scaled general average with two decimal places
                                    echo "<td>" . number_format($finalterm_general_average_scaled, 2) . "</td>";
                                    echo "<td>" . number_format(($finalterm_general_average_scaled/60)*100, 2) . "</td>";
                                
                                    $general_average = $midterm_general_average_scaled + $finalterm_general_average_scaled ;
                                    echo "<td>" . number_format($general_average, 2) . "</td>";
                                        } else {
                                            
                                        }

                              
                                 
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