<?php
                require_once "config.php";
                include 'sidebar.php';
                include 'navbar.php';


            
                $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
           
               

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


                             // Fetch student data from the database based on the class_id and order by lastname in ascending order
                                    $sql = "SELECT * FROM tblstudents WHERE class_id = ? ORDER BY lastname ASC";
                                    $stmt = $connection->prepare($sql);
                                    $stmt->bind_param("s", $class_id);
                                    $stmt->execute();

                                    // Store student data in an array
                                    $students = array();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $students[] = $row;
                                        }
                                    }



                                // para sa midterm summative
                                $midtermSummativesAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Summative' ORDER BY datetime ASC";
                                $midtermSummativesAssessmentStmt = $connection->prepare($midtermSummativesAssessmentSql);
                                $midtermSummativesAssessmentStmt->bind_param("s", $class_id);
                                $midtermSummativesAssessmentStmt->execute();
                                $midtermSummativesAssessmentResult = $midtermSummativesAssessmentStmt->get_result();


                                $midtermSummativesAssessmentTitles = array();
                                while ($row = $midtermSummativesAssessmentResult->fetch_assoc()) {
                                    $midtermSummativesAssessmentTitles[] = $row['assesment_id'];
                                }

  

                                //para sa midterm Exam
                                $midtermExamAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Exam' ORDER BY datetime ASC";
                                $midtermExamAssessmentStmt = $connection->prepare($midtermExamAssessmentSql);
                                $midtermExamAssessmentStmt->bind_param("s", $class_id);
                                $midtermExamAssessmentStmt->execute();
                                $midtermExamAssessmentResult = $midtermExamAssessmentStmt->get_result();


                                $midtermExamAssessmentTitles = array();
                                while ($row = $midtermExamAssessmentResult->fetch_assoc()) {
                                    $midtermExamAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa midterm Output
                                $midtermOutputAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Output' ORDER BY datetime ASC";
                                $midtermOutputAssessmentStmt = $connection->prepare($midtermOutputAssessmentSql);
                                $midtermOutputAssessmentStmt->bind_param("s", $class_id);
                                $midtermOutputAssessmentStmt->execute();
                                $midtermOutputAssessmentResult = $midtermOutputAssessmentStmt->get_result();


                                $midtermOutputAssessmentTitles = array();
                                while ($row = $midtermOutputAssessmentResult->fetch_assoc()) {
                                    $midtermOutputAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa midterm Laboratory
                                $midtermLaboratoryAssessmentSql =  "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Participation' ORDER BY datetime ASC";
                                $midtermLaboratoryAssessmentStmt = $connection->prepare($midtermLaboratoryAssessmentSql);
                                $midtermLaboratoryAssessmentStmt->bind_param("s", $class_id);
                                $midtermLaboratoryAssessmentStmt->execute();
                                $midtermLaboratoryAssessmentResult = $midtermLaboratoryAssessmentStmt->get_result();


                                $midtermLaboratoryAssessmentTitles = array();
                                while ($row = $midtermLaboratoryAssessmentResult->fetch_assoc()) {
                                    $midtermLaboratoryAssessmentTitles[] = $row['assesment_id'];
                                }

                                $midtermLaboratoryAssessmentSql1 =  "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Activity' ORDER BY datetime ASC";
                                $midtermLaboratoryAssessmentStmt1 = $connection->prepare($midtermLaboratoryAssessmentSql1);
                                $midtermLaboratoryAssessmentStmt1->bind_param("s", $class_id);
                                $midtermLaboratoryAssessmentStmt1->execute();
                                $midtermLaboratoryAssessmentResult1 = $midtermLaboratoryAssessmentStmt1->get_result();


                                $midtermLaboratoryAssessmentTitles1 = array();
                                while ($row = $midtermLaboratoryAssessmentResult1->fetch_assoc()) {
                                    $midtermLaboratoryAssessmentTitles1[] = $row['assesment_id'];
                                }

                                //para sa midterm Assignments
                                $midtermAssignmentAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Assignment' ORDER BY datetime ASC";
                                $midtermAssignmentAssessmentStmt = $connection->prepare($midtermAssignmentAssessmentSql);
                                $midtermAssignmentAssessmentStmt->bind_param("s", $class_id);
                                $midtermAssignmentAssessmentStmt->execute();
                                $midtermAssignmentAssessmentResult = $midtermAssignmentAssessmentStmt->get_result();


                                $midtermAssignmentAssessmentTitles = array();
                                while ($row = $midtermAssignmentAssessmentResult->fetch_assoc()) {
                                    $midtermAssignmentAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa midterm participation
                                $midtermParticipationAssessmentSql =  "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Engagement' ORDER BY datetime ASC";
                                $midtermParticipationAssessmentStmt = $connection->prepare($midtermParticipationAssessmentSql);
                                $midtermParticipationAssessmentStmt->bind_param("s", $class_id);
                                $midtermParticipationAssessmentStmt->execute();
                                $midtermParticipationAssessmentResult = $midtermParticipationAssessmentStmt->get_result();


                                $midtermParticipationAssessmentTitles = array();
                                while ($row = $midtermParticipationAssessmentResult->fetch_assoc()) {
                                    $midtermParticipationAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa midterm behavior
                                $midtermBehaviorAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'MidTerm' AND assesment_type = 'Behavior' ORDER BY datetime ASC";
                                $midtermBehaviorAssessmentStmt = $connection->prepare($midtermBehaviorAssessmentSql);
                                $midtermBehaviorAssessmentStmt->bind_param("s", $class_id);
                                $midtermBehaviorAssessmentStmt->execute();
                                $midtermBehaviornAssessmentResult = $midtermBehaviorAssessmentStmt->get_result();


                                $midtermBehaviorAssessmentTitles = array();
                                while ($row = $midtermBehaviornAssessmentResult->fetch_assoc()) {
                                    $midtermBehaviorAssessmentTitles[] = $row['assesment_id'];
                                }

                                    // midterm //
                                $midtermSummativesColspan = count($midtermSummativesAssessmentTitles) ?: 1;
                            
                                $midtermExamColspan = count($midtermExamAssessmentTitles) ?: 1;
                                $midtermOutputColspan = count($midtermOutputAssessmentTitles) ?: 1;
                                $midtermLaboratoryColspan = count($midtermLaboratoryAssessmentTitles) ?: 1;
                                $midtermLaboratoryColspan1 = count($midtermLaboratoryAssessmentTitles1) ?: 1;
                                $midtermAssignmentColspan = count($midtermAssignmentAssessmentTitles) ?: 1;
                                $midtermParticipationColspan = count($midtermParticipationAssessmentTitles) ?: 1;
                                $midtermBehaviorColspan = count($midtermBehaviorAssessmentTitles) ?: 1;

    
                                // para sa finalterm summative
                                $finaltermSummativesAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Summative' ORDER BY datetime ASC";
                                $finaltermSummativesAssessmentStmt = $connection->prepare($finaltermSummativesAssessmentSql);
                                $finaltermSummativesAssessmentStmt->bind_param("s", $class_id);
                                $finaltermSummativesAssessmentStmt->execute();
                                $finaltermSummativesAssessmentResult = $finaltermSummativesAssessmentStmt->get_result();

                                $finaltermSummativesAssessmentTitles = array();
                                while ($row = $finaltermSummativesAssessmentResult->fetch_assoc()) {
                                $finaltermSummativesAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa finalterm exam
                                $finaltermExamAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Exam' ORDER BY datetime ASC";
                                $finaltermExamAssessmentStmt = $connection->prepare($finaltermExamAssessmentSql);
                                $finaltermExamAssessmentStmt->bind_param("s", $class_id);
                                $finaltermExamAssessmentStmt->execute();
                                $finaltermExamAssessmentResult = $finaltermExamAssessmentStmt->get_result();
                    
                                $finaltermExamAssessmentTitles = array();
                                while ($row = $finaltermExamAssessmentResult->fetch_assoc()) {
                                    $finaltermExamAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa finalterm output
                                $finaltermOutputAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Output' ORDER BY datetime ASC";
                                $finaltermOutputAssessmentStmt = $connection->prepare($finaltermOutputAssessmentSql);
                                $finaltermOutputAssessmentStmt->bind_param("s", $class_id);
                                $finaltermOutputAssessmentStmt->execute();
                                $finaltermOutputAssessmentResult = $finaltermOutputAssessmentStmt->get_result();
                    
                                $finaltermOutputAssessmentTitles = array();
                                while ($row = $finaltermOutputAssessmentResult->fetch_assoc()) {
                                    $finaltermOutputAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa finalterm laboratory
                                $finaltermLaboratoryAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Participation' ORDER BY datetime ASC";
                                $finaltermLaboratoryAssessmentStmt = $connection->prepare($finaltermLaboratoryAssessmentSql);
                                $finaltermLaboratoryAssessmentStmt->bind_param("s", $class_id);
                                $finaltermLaboratoryAssessmentStmt->execute();
                                $finaltermLaboratoryAssessmentResult = $finaltermLaboratoryAssessmentStmt->get_result();
                    
                                $finaltermLaboratoryAssessmentTitles = array();
                                while ($row = $finaltermLaboratoryAssessmentResult->fetch_assoc()) {
                                    $finaltermLaboratoryAssessmentTitles[] = $row['assesment_id'];
                                }

                                //para sa finalterm laboratory
                                $finaltermLaboratoryAssessmentSql1 = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Activity' ORDER BY datetime ASC";
                                $finaltermLaboratoryAssessmentStmt1 = $connection->prepare($finaltermLaboratoryAssessmentSql1);
                                $finaltermLaboratoryAssessmentStmt1->bind_param("s", $class_id);
                                $finaltermLaboratoryAssessmentStmt1->execute();
                                $finaltermLaboratoryAssessmentResult1 = $finaltermLaboratoryAssessmentStmt1->get_result();
                    
                                $finaltermLaboratoryAssessmentTitles1 = array();
                                while ($row = $finaltermLaboratoryAssessmentResult1->fetch_assoc()) {
                                    $finaltermLaboratoryAssessmentTitles1[] = $row['assesment_id'];
                                }

                                //para sa finalterm assignment
                                $finaltermAssignmentAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Assignment' ORDER BY datetime ASC";
                                $finaltermAssignmentAssessmentStmt = $connection->prepare($finaltermAssignmentAssessmentSql);
                                $finaltermAssignmentAssessmentStmt->bind_param("s", $class_id);
                                $finaltermAssignmentAssessmentStmt->execute();
                                $finaltermAssignmentAssessmentResult = $finaltermAssignmentAssessmentStmt->get_result();
                    
                                $finaltermAssignmentAssessmentTitles = array();
                                while ($row = $finaltermAssignmentAssessmentResult->fetch_assoc()) {
                                    $finaltermAssignmentAssessmentTitles[] = $row['assesment_id'];
                                }

                              //para sa finalterm participation
                              $finaltermParticipationAssessmentSql = "SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Engagement' ORDER BY datetime ASC";
                              $finaltermParticipationAssessmentStmt = $connection->prepare($finaltermParticipationAssessmentSql);
                              $finaltermParticipationAssessmentStmt->bind_param("s", $class_id);
                              $finaltermParticipationAssessmentStmt->execute();
                              $finaltermParticipationAssessmentResult = $finaltermParticipationAssessmentStmt->get_result();
                  
                              $finaltermParticipationAssessmentTitles = array();
                              while ($row = $finaltermParticipationAssessmentResult->fetch_assoc()) {
                                $finaltermParticipationAssessmentTitles[] = $row['assesment_id'];
                              }

                               //para sa finalterm behavior
                               $finaltermBehaviorAssessmentSql ="SELECT * FROM tblassesment WHERE class_id = ? AND term = 'Final' AND assesment_type = 'Behavior' ORDER BY datetime ASC";
                               $finaltermBehaviorAssessmentStmt = $connection->prepare($finaltermBehaviorAssessmentSql);
                               $finaltermBehaviorAssessmentStmt->bind_param("s", $class_id);
                               $finaltermBehaviorAssessmentStmt->execute();
                               $finaltermBehaviorAssessmentResult = $finaltermBehaviorAssessmentStmt->get_result();
                   
                               $finaltermBehaviorAssessmentTitles = array();
                               while ($row = $finaltermBehaviorAssessmentResult->fetch_assoc()) {
                                 $finaltermBehaviorAssessmentTitles[] = $row['assesment_id'];
                               }

                                $finaltermSummativesColspan = count($finaltermSummativesAssessmentTitles) ?: 1;
                            
                                $finaltermExamColspan = count($finaltermExamAssessmentTitles) ?: 1;
                                $finaltermOutputColspan = count($finaltermOutputAssessmentTitles) ?: 1;
                                $finaltermLaboratoryColspan = count($finaltermLaboratoryAssessmentTitles) ?: 1;
                                $finaltermLaboratory1Colspan = count($finaltermLaboratoryAssessmentTitles1) ?: 1;
                                $finaltermAssignmentColspan = count($finaltermAssignmentAssessmentTitles) ?: 1;
                                $finaltermParticipationColspan = count($finaltermParticipationAssessmentTitles) ?: 1;
                                $finaltermBehaviorColspan = count($finaltermBehaviorAssessmentTitles) ?: 1;

            

                                // Add the class_id and user_id as query parameters to the Add Assessment link
                                $addAssessmentLink = "addassesmentnonlabIT.php?class_id=$class_id";
                                $addStudentLink = "addstudent.php?class_id=$class_id";
                                $addAttendance = "attendance.php?class_id=$class_id";
                                $Average = "average.php?class_id=$class_id";
                                $managestudent = "managestudent.php?class_id=$class_id";
                                $manageassesment = "manageassesment.php?class_id=$class_id";
                                $addschedule = "addschedule.php?class_id=$class_id";
                                $viewschedule = "viewallschedule.php?class_id=$class_id";
                             
 ?>

                <!DOCTYPE html>
                <html lang="en">
                <head>

                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                        <title>CLASSRECORD</title>
                                    <style>
                                        body {
                                        
                                            margin-left: 50px;
                                            font-family: Arial, sans-serif;
                                            
                                        }

                                        table {
                                            border-collapse: collapse;
                                            margin-top: 30px;
                                            margin-left: 200px;
                                           
                                        
                                        
                                        }
                                        .container{
                                            margin-left:150px;
                                            margin-right: 10px;
                                        }
                                    
                                        th, td {
                                            border: 1px solid black;
                                            padding: 8px;
                                            text-align: center;
                                        }

                                        th {
                                            background-color: #f2f2f2;
                                        }

                                        .assessment-title {
                                        background-color: #5cf7b9;
                                        padding: 10px; /* Add padding for better visibility */
                                        transition: background-color 0.3s ease; /* Add a smooth transition effect */
                                        }

                                        .assessment-title .datetime {
                                            display: none; /* Hide datetime by default */
                                        }

                                        .assessment-title:hover {
                                            background-color: #a3e0c3; /* Change this color to your desired hover color */
                                            cursor: pointer; /* Change cursor to pointer on hover */
                                        }

                                        .assessment-title:hover .datetime {
                                            display: inline; /* Show datetime on hover */
                                        }
                                        /* Automatic width adjustment for Name column */
                                        .name-column {
                                                white-space: nowrap; /* Prevent line breaks within names */
                                            }
                                            .student-id-column {
                                            white-space: nowrap; /* Prevent line breaks within student IDs */
                                            text-align: left;
                                        }
                                        image{
                                            width: 50px;
                                            height: 50px;
                                        }
                                </style>


                                                <div class="container mt-4">
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end mt-5">

                                                     
                                                             <a href="<?php echo $viewschedule; ?>" class="btn btn-primary ms-1"> View Schedule</a>
                                            
                                                             <a href="<?php echo $addschedule; ?>" class="btn btn-primary ms-1"> Add Schedule</a>

                                                             <a href="<?php echo $addStudentLink; ?>" class="btn btn-primary ms-1">Add Student</a>

                                                             <a href="<?php echo $managestudent; ?>" class="btn btn-primary ms-1">Manage Student</a>

                                                             <a href="<?php echo $addAssessmentLink; ?>" class="btn btn-primary ms-1">Add Assessment</a>

                                                             <a href="<?php echo $manageassesment; ?>" class="btn btn-primary ms-1">Manage Assessment</a>

                                                             <a href="<?php echo $addAttendance; ?>" class="btn btn-primary ms-1">Attendance</a>

                                                             <a href="<?php echo $Average; ?>" class="btn btn-primary ms-1">Average</a>
                                                        </div>
                                                    </div>
                                                </div>

                </head>
<body>
<form action='nonlabBSITtable.php?class_id=<?php echo $class_id; ?>' method='post'>
<input type="hidden" name="class_id" value="<?= $class_id; ?>">
    <table>
                            <thead>
                                <tr><th rowspan="4" id="Names" style="background-color: #66d0fa;">Student Id</th>
                                <th rowspan="4" id="Names" style="background-color: #66d0fa;">Name</th>

                                <!-- Set dynamic colspan for "MidTerm" based on the total count of assessments -->
                                <th colspan="<?= $midtermSummativesColspan  + $midtermExamColspan + $midtermOutputColspan + $midtermLaboratoryColspan + $midtermLaboratoryColspan1 + $midtermAssignmentColspan + $midtermParticipationColspan + $midtermBehaviorColspan ?: 8?>" style="background-color: #5cf7b9;">
                                Mid Term (40)      
                                </th>

                                <th colspan="<?= $finaltermSummativesColspan +  $midtermExamColspan + $finaltermOutputColspan + $finaltermLaboratoryColspan + $finaltermLaboratory1Colspan + $finaltermAssignmentColspan + $finaltermParticipationColspan + $finaltermBehaviorColspan ?: 8 ?>" style="background-color: #fcaecb;">
                                Final Term (60)
                                </th>
                                </tr>
                                <tr>

                                <!-- Set dynamic colspans for "Knowledge," "Skills," and "Attitude" based on the count of assessments -->
                                <!-- Mideterm KSA -->
                                <th colspan="<?= $midtermSummativesColspan + $midtermExamColspan ?: 2 ?>" style="background-color: #5cf7b9;">Knowledge (50)</th>
                                <th colspan="<?= $midtermOutputColspan + $midtermLaboratoryColspan + $midtermLaboratoryColspan1 + $midtermAssignmentColspan ?: 4 ?>" style="background-color: #5cf7b9;">Skill (40)</th>
                                <th colspan="<?= $midtermParticipationColspan + $midtermBehaviorColspan ?: 2 ?>" style="background-color: #5cf7b9;">Attitude (10)</th>
                                                

                                <!-- Finalterm KSA -->
                                <th colspan="<?= $finaltermSummativesColspan + $finaltermExamColspan ?: 2 ?>" style="background-color: #fcaecb;">Knowledge (50)</th>
                                <th colspan="<?= $finaltermOutputColspan + $finaltermLaboratoryColspan + $finaltermLaboratory1Colspan + $finaltermAssignmentColspan ?: 4 ?>" style="background-color: #fcaecb;">Skill (40)</th>
                                <th colspan="<?= $finaltermParticipationColspan + $finaltermBehaviorColspan ?: 2 ?>" style="background-color: #fcaecb;">Attitude (10)</th>

                                </tr>
                                <tr>
                                <!-- Midterm Assessment Titles -->
                                <th colspan="<?= count($midtermSummativesAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Summative(40)</th>

                                <th colspan="<?= count($midtermExamAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Exam(60)</th>
                                <th colspan="<?= count($midtermOutputAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Output(40)</th>
                                <th colspan="<?= count($midtermLaboratoryAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Participation(30)</th>
                                <th colspan="<?= count($midtermLaboratoryAssessmentTitles1) ?: 1 ?>" style="background-color: #5cf7b9;">Activity(20)</th>
                                <th colspan="<?= count($midtermAssignmentAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Assignment(10)</th>
                                <th colspan="<?= count($midtermParticipationAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Engagement(50)</th>
                                <th colspan="<?= count($midtermBehaviorAssessmentTitles) ?: 1 ?>" style="background-color: #5cf7b9;">Behavior(50)</th>


                                <!-- Finalterm Assessment Titles -->
                                <th colspan="<?= count($finaltermSummativesAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Summative(40)</th>
                                <th colspan="<?= count($finaltermExamAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Exam(60)</th>
                                <th colspan="<?= count($finaltermOutputAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Output(40)</th>
                                <th colspan="<?= count($finaltermLaboratoryAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Participation(30)</th>
                                <th colspan="<?= count($finaltermLaboratoryAssessmentTitles1) ?: 1 ?>" style="background-color: #fcaecb;">Activity(20)</th>
                                <th colspan="<?= count($finaltermAssignmentAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Assignment(10)</th>
                                <th colspan="<?= count($finaltermParticipationAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Engagement(50)</th>
                                <th colspan="<?= count($finaltermBehaviorAssessmentTitles) ?: 1 ?>" style="background-color: #fcaecb;">Behavior(50)</th>

                                </tr>
                                    <tr>

                                            <?php foreach ($midtermSummativesAssessmentTitles as $summativetitle): ?>
                                            <?php
                                            // Fetch the assessment title for the specific assessment ID
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("s", $summativetitle);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the assessment title, total item value, and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermSummativesAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>



                                            <?php foreach ($midtermExamAssessmentTitles as $examTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific exam
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $examTitle, $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermExamAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>





                                            <?php foreach ($midtermOutputAssessmentTitles as $outputTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific output assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $outputTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();

                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];

                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermOutputAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>

                                                                



                                            <?php foreach ($midtermLaboratoryAssessmentTitles as $labTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific laboratory assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $labTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermLaboratoryAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>


                                            <?php foreach ($midtermLaboratoryAssessmentTitles1 as $lab1Title): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific laboratory assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $lab1Title , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermLaboratoryAssessmentTitles1)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>



                                                                                                    
                                            <?php foreach ($midtermAssignmentAssessmentTitles as $assignmentTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific assignment assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $assignmentTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermAssignmentAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>






                                            <?php foreach ($midtermParticipationAssessmentTitles as $participationTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific participation assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $participationTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>

                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermParticipationAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($midtermBehaviorAssessmentTitles as $behaviorTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific behavior assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $behaviorTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #5cf7b9;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($midtermBehaviorAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #5cf7b9;"></th>
                                            <?php endif; ?>






                                                <!-- Final Term Assessments -->

                                            <?php foreach ($finaltermSummativesAssessmentTitles as $finalsummativeTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term summative assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finalsummativeTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermSummativesAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($finaltermExamAssessmentTitles as $finalexamTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term exam assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finalexamTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermExamAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($finaltermOutputAssessmentTitles as $finaloutputTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term output assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finaloutputTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermOutputAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>
                                            




                                            <?php foreach ($finaltermLaboratoryAssessmentTitles as $finallabTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term laboratory assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finallabTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermLaboratoryAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>
                                            


                                            <?php foreach ($finaltermLaboratoryAssessmentTitles1 as $finallabTitle1): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term laboratory assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finallabTitle1 , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermLaboratoryAssessmentTitles1)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($finaltermAssignmentAssessmentTitles as $finalassignmentTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term assignment assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finalassignmentTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermAssignmentAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($finaltermParticipationAssessmentTitles as $finalparticipationTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term participation assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finalparticipationTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermParticipationAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>




                                            <?php foreach ($finaltermBehaviorAssessmentTitles as $finalbehaviorTitle): ?>
                                            <?php
                                            // Fetch the total item and datetime for the specific final term behavior assessment
                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ? AND class_id = ?";
                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                            $stmt_fetch_data->bind_param("ss", $finalbehaviorTitle , $class_id);
                                            $stmt_fetch_data->execute();
                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                            // Get the total item value and datetime
                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                            $assessmentTitle = $row['assesment_title'];
                                            $totalItemValue = $row['total_item'];
                                            $datetime = $row['datetime'];
                                            ?>
                                            <th class="assessment-title" colspan="1" style="background-color: #fcaecb;" title="<?= $datetime ?>">
                                            <span><?= $assessmentTitle ?></span>
                                            <br>
                                            (<?= $totalItemValue ?>)
                                            </th>
                                            <?php endforeach; ?>
                                            <?php if (empty($finaltermBehaviorAssessmentTitles)): ?>
                                            <th colspan="1" style="background-color: #fcaecb;"></th>
                                            <?php endif; ?>
                                            </tr>
                            </thead>



                            <tbody>
                                
                                    <!-- Display student names and scores -->
                                    <?php
                                    $studentCounter = 1; // Initialize the counter
                                    foreach ($students as $student): ?>
                                    <tr style="width: 200px;">
                                    <td class='student-id-column'> <?= $student['student_id'] ?></td>  
                                    <td class='name-column' style='text-align: left;'><?= $studentCounter ?>.) <?= $student['lastname'] ?>,  <?= $student['fname'] ?> <?= $student['middlename'] ?>.</td>     
                                    <script>
                                        function validateScore(input, totalItemValue) {
                                            // Convert the input value to a number
                                            var enteredScore = parseInt(input.value, 10);

                                            // Check if the entered score is greater than the total item value
                                            if (enteredScore > totalItemValue) {
                                                alert("Cannot Add Score Higher than (" + totalItemValue + ")");
                                                // Reset the input value to the total item value
                                                input.value = totalItemValue;
                                            }
                                        }
                                        </script>

                                                
                                                
                                    <?php if (!empty($midtermSummativesAssessmentTitles)): ?>
                                            <?php foreach ($midtermSummativesAssessmentTitles as $summativetitle): ?>
                                                <td>
                                                    <?php
                                                    // Fetch the assessment title for the specific assessment ID
                                                    $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                    $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                    $stmt_fetch_data->bind_param("s", $summativetitle);
                                                    $stmt_fetch_data->execute();
                                                    $result_fetch_data = $stmt_fetch_data->get_result();
                                                    // Get the assessment title, total item value, and datetime
                                                    $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                    $assessmentTitle = $row['assesment_title'];
                                                    $totalItemValue = $row['total_item'];
                                                    $datetime = $row['datetime'];

                                                    // Fetch the score from the database
                                                    $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                    $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                    $stmt_fetch_score->bind_param("ss", $student['student_id'], $summativetitle);
                                                    $stmt_fetch_score->execute();
                                                    $result_fetch_score = $stmt_fetch_score->get_result();

                                                    // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                    $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                    // Check if there are updated scores from the form submission
                                                    $scoreValue = isset($_POST['scores'][$student['student_id']][$summativetitle]) ? $_POST['scores'][$student['student_id']][$summativetitle] : $scoreValue;

                                                    echo "<input type='text' name='scores[{$student['student_id']}][$summativetitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <td></td> <!-- Display an empty cell if no assessments -->
                                        <?php endif; ?>



                                        <?php if (!empty($midtermExamAssessmentTitles)): ?>
                                                <?php foreach ($midtermExamAssessmentTitles as $examTitle): ?>
                                                    <td>
                                                        <?php
                                                        // Fetch the assessment title for the specific assessment ID
                                                        $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                        $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                        $stmt_fetch_data->bind_param("s", $examTitle);
                                                        $stmt_fetch_data->execute();
                                                        $result_fetch_data = $stmt_fetch_data->get_result();
                                                        // Get the assessment title, total item value, and datetime
                                                        $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                        $assessmentTitle = $row['assesment_title'];
                                                        $totalItemValue = $row['total_item'];
                                                        $datetime = $row['datetime'];

                                                        // Fetch the score from the database
                                                        $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                        $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                        $stmt_fetch_score->bind_param("ss", $student['student_id'], $examTitle);
                                                        $stmt_fetch_score->execute();
                                                        $result_fetch_score = $stmt_fetch_score->get_result();

                                                        // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                        $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                        // Check if there are updated scores from the form submission
                                                        $scoreValue = isset($_POST['scores'][$student['student_id']][$examTitle]) ? $_POST['scores'][$student['student_id']][$examTitle] : $scoreValue;

                                                        echo "<input type='text' name='scores[{$student['student_id']}][$examTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <td></td> <!-- Display an empty cell if no participation assessments -->
                                            <?php endif; ?>





                                            <?php if (!empty($midtermOutputAssessmentTitles)): ?>
                                                    <?php foreach ($midtermOutputAssessmentTitles as $outputTitle): ?>
                                                        <td>
                                                            <?php
                                                            // Fetch the assessment title for the specific assessment ID
                                                            $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                            $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                            $stmt_fetch_data->bind_param("s", $outputTitle);
                                                            $stmt_fetch_data->execute();
                                                            $result_fetch_data = $stmt_fetch_data->get_result();
                                                            // Get the assessment title, total item value, and datetime
                                                            $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                            $assessmentTitle = $row['assesment_title'];
                                                            $totalItemValue = $row['total_item'];
                                                            $datetime = $row['datetime'];

                                                            // Fetch the score from the database
                                                            $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                            $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                            $stmt_fetch_score->bind_param("ss", $student['student_id'], $outputTitle);
                                                            $stmt_fetch_score->execute();
                                                            $result_fetch_score = $stmt_fetch_score->get_result();

                                                            // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                            $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                            // Check if there are updated scores from the form submission
                                                            $scoreValue = isset($_POST['scores'][$student['student_id']][$outputTitle]) ? $_POST['scores'][$student['student_id']][$outputTitle] : $scoreValue;

                                                            echo "<input type='text' name='scores[{$student['student_id']}][$outputTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <td></td> <!-- Display an empty cell if no participation assessments -->
                                                <?php endif; ?>





                                                        
                                                <?php if (!empty($midtermLaboratoryAssessmentTitles)): ?>
                                                        <?php foreach ($midtermLaboratoryAssessmentTitles as $labTitle): ?>
                                                            <td>
                                                                <?php
                                                                // Fetch the assessment title for the specific assessment ID
                                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                $stmt_fetch_data->bind_param("s", $labTitle);
                                                                $stmt_fetch_data->execute();
                                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                                // Get the assessment title, total item value, and datetime
                                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                $assessmentTitle = $row['assesment_title'];
                                                                $totalItemValue = $row['total_item'];
                                                                $datetime = $row['datetime'];

                                                                // Fetch the score from the database
                                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $labTitle);
                                                                $stmt_fetch_score->execute();
                                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                // Check if there are updated scores from the form submission
                                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$labTitle]) ? $_POST['scores'][$student['student_id']][$labTitle] : $scoreValue;

                                                                echo "<input type='text' name='scores[{$student['student_id']}][$labTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                                    <?php endif; ?>

                                                    <?php if (!empty($midtermLaboratoryAssessmentTitles1)): ?>
                                                        <?php foreach ($midtermLaboratoryAssessmentTitles1 as $lab1Title): ?>
                                                            <td>
                                                                <?php
                                                                // Fetch the assessment title for the specific assessment ID
                                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                $stmt_fetch_data->bind_param("s", $lab1Title);
                                                                $stmt_fetch_data->execute();
                                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                                // Get the assessment title, total item value, and datetime
                                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                $assessmentTitle = $row['assesment_title'];
                                                                $totalItemValue = $row['total_item'];
                                                                $datetime = $row['datetime'];

                                                                // Fetch the score from the database
                                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $lab1Title);
                                                                $stmt_fetch_score->execute();
                                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                // Check if there are updated scores from the form submission
                                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$lab1Title]) ? $_POST['scores'][$student['student_id']][$lab1Title] : $scoreValue;

                                                                echo "<input type='text' name='scores[{$student['student_id']}][$lab1Title]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                                    <?php endif; ?>



                                                    <?php if (!empty($midtermAssignmentAssessmentTitles)): ?>
                                                        <?php foreach ($midtermAssignmentAssessmentTitles as $assignmentTitle): ?>
                                                            <td>
                                                                <?php
                                                                // Fetch the assessment title for the specific assessment ID
                                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                $stmt_fetch_data->bind_param("s", $assignmentTitle);
                                                                $stmt_fetch_data->execute();
                                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                                // Get the assessment title, total item value, and datetime
                                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                $assessmentTitle = $row['assesment_title'];
                                                                $totalItemValue = $row['total_item'];
                                                                $datetime = $row['datetime'];

                                                                // Fetch the score from the database
                                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $assignmentTitle);
                                                                $stmt_fetch_score->execute();
                                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                // Check if there are updated scores from the form submission
                                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$assignmentTitle]) ? $_POST['scores'][$student['student_id']][$assignmentTitle] : $scoreValue;

                                                                echo "<input type='text' name='scores[{$student['student_id']}][$assignmentTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                                    <?php endif; ?>

                                                    
                                                    <?php if (!empty($midtermParticipationAssessmentTitles)): ?>
                                                                <?php foreach ($midtermParticipationAssessmentTitles as $participationTitle): ?>
                                                                    <td>
                                                                        <?php
                                                                        // Fetch the assessment title for the specific assessment ID
                                                                        $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                        $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                        $stmt_fetch_data->bind_param("s", $participationTitle);
                                                                        $stmt_fetch_data->execute();
                                                                        $result_fetch_data = $stmt_fetch_data->get_result();
                                                                        // Get the assessment title, total item value, and datetime
                                                                        $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                        $assessmentTitle = $row['assesment_title'];
                                                                        $totalItemValue = $row['total_item'];
                                                                        $datetime = $row['datetime'];

                                                                        // Fetch the score from the database
                                                                        $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                        $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                        $stmt_fetch_score->bind_param("ss", $student['student_id'], $participationTitle);
                                                                        $stmt_fetch_score->execute();
                                                                        $result_fetch_score = $stmt_fetch_score->get_result();

                                                                        // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                        $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                        // Check if there are updated scores from the form submission
                                                                        $scoreValue = isset($_POST['scores'][$student['student_id']][$participationTitle]) ? $_POST['scores'][$student['student_id']][$participationTitle] : $scoreValue;

                                                                        echo "<input type='text' name='scores[{$student['student_id']}][$participationTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                        ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <td></td> <!-- Display an empty cell if no participation assessments -->
                                                            <?php endif; ?>





                                                            <?php if (!empty($midtermBehaviorAssessmentTitles)): ?>
                                                                        <?php foreach ($midtermBehaviorAssessmentTitles as $behaviorTitle): ?>
                                                                            <td>
                                                                                <?php
                                                                                // Fetch the assessment title for the specific assessment ID
                                                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                                $stmt_fetch_data->bind_param("s", $behaviorTitle);
                                                                                $stmt_fetch_data->execute();
                                                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                                                // Get the assessment title, total item value, and datetime
                                                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                                $assessmentTitle = $row['assesment_title'];
                                                                                $totalItemValue = $row['total_item'];
                                                                                $datetime = $row['datetime'];

                                                                                // Fetch the score from the database
                                                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $behaviorTitle);
                                                                                $stmt_fetch_score->execute();
                                                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                                // Check if there are updated scores from the form submission
                                                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$behaviorTitle]) ? $_POST['scores'][$student['student_id']][$behaviorTitle] : $scoreValue;

                                                                                echo "<input type='text' name='scores[{$student['student_id']}][$behaviorTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                                ?>
                                                                            </td>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                                                    <?php endif; ?>


                                                             <?php if (!empty($finaltermSummativesAssessmentTitles)): ?>
                                                                <?php foreach ($finaltermSummativesAssessmentTitles as $finalsummativeTitle): ?>
                                                                    <td>
                                                                        <?php
                                                                        // Fetch the assessment title for the specific assessment ID
                                                                        $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                        $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                        $stmt_fetch_data->bind_param("s", $finalsummativeTitle);
                                                                        $stmt_fetch_data->execute();
                                                                        $result_fetch_data = $stmt_fetch_data->get_result();
                                                                        // Get the assessment title, total item value, and datetime
                                                                        $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                        $assessmentTitle = $row['assesment_title'];
                                                                        $totalItemValue = $row['total_item'];
                                                                        $datetime = $row['datetime'];

                                                                        // Fetch the score from the database
                                                                        $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                        $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                        $stmt_fetch_score->bind_param("ss", $student['student_id'], $finalsummativeTitle);
                                                                        $stmt_fetch_score->execute();
                                                                        $result_fetch_score = $stmt_fetch_score->get_result();

                                                                        // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                        $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                        // Check if there are updated scores from the form submission
                                                                        $scoreValue = isset($_POST['scores'][$student['student_id']][$finalsummativeTitle]) ? $_POST['scores'][$student['student_id']][$finalsummativeTitle] : $scoreValue;

                                                                        echo "<input type='text' name='scores[{$student['student_id']}][$finalsummativeTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                        ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <td></td> <!-- Display an empty cell if no participation assessments -->
                                                            <?php endif; ?>


                                                    
                                                            <?php if (!empty($finaltermExamAssessmentTitles)): ?>
                                                                <?php foreach ($finaltermExamAssessmentTitles as $finalexamTitle): ?>
                                                                    <td>
                                                                        <?php
                                                                        // Fetch the assessment title for the specific assessment ID
                                                                        $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                                        $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                                        $stmt_fetch_data->bind_param("s", $finalexamTitle);
                                                                        $stmt_fetch_data->execute();
                                                                        $result_fetch_data = $stmt_fetch_data->get_result();
                                                                        // Get the assessment title, total item value, and datetime
                                                                        $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                                        $assessmentTitle = $row['assesment_title'];
                                                                        $totalItemValue = $row['total_item'];
                                                                        $datetime = $row['datetime'];

                                                                        // Fetch the score from the database
                                                                        $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                                        $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                                        $stmt_fetch_score->bind_param("ss", $student['student_id'], $finalexamTitle);
                                                                        $stmt_fetch_score->execute();
                                                                        $result_fetch_score = $stmt_fetch_score->get_result();

                                                                        // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                                        $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                                        // Check if there are updated scores from the form submission
                                                                        $scoreValue = isset($_POST['scores'][$student['student_id']][$finalexamTitle]) ? $_POST['scores'][$student['student_id']][$finalexamTitle] : $scoreValue;

                                                                        echo "<input type='text' name='scores[{$student['student_id']}][$finalexamTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                                        ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <td></td> <!-- Display an empty cell if no participation assessments -->
                                                            <?php endif; ?>




                                         <?php if (!empty($finaltermOutputAssessmentTitles)): ?>
                                            <?php foreach ($finaltermOutputAssessmentTitles as $finaloutputTitle): ?>
                                                <td>
                                                    <?php
                                                    // Fetch the assessment title for the specific assessment ID
                                                    $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                    $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                    $stmt_fetch_data->bind_param("s", $finaloutputTitle);
                                                    $stmt_fetch_data->execute();
                                                    $result_fetch_data = $stmt_fetch_data->get_result();
                                                    // Get the assessment title, total item value, and datetime
                                                    $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                    $assessmentTitle = $row['assesment_title'];
                                                    $totalItemValue = $row['total_item'];
                                                    $datetime = $row['datetime'];

                                                    // Fetch the score from the database
                                                    $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                    $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                    $stmt_fetch_score->bind_param("ss", $student['student_id'], $finaloutputTitle);
                                                    $stmt_fetch_score->execute();
                                                    $result_fetch_score = $stmt_fetch_score->get_result();

                                                    // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                    $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                    // Check if there are updated scores from the form submission
                                                    $scoreValue = isset($_POST['scores'][$student['student_id']][$finaloutputTitle]) ? $_POST['scores'][$student['student_id']][$finaloutputTitle] : $scoreValue;

                                                    echo "<input type='text' name='scores[{$student['student_id']}][$finaloutputTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <td></td> <!-- Display an empty cell if no participation assessments -->
                                        <?php endif; ?>

                                                    
                                        <?php if (!empty($finaltermLaboratoryAssessmentTitles)): ?>
                                        <?php foreach ($finaltermLaboratoryAssessmentTitles as $finallabTitle): ?>
                                            <td>
                                                <?php
                                                // Fetch the assessment title for the specific assessment ID
                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                $stmt_fetch_data->bind_param("s", $finallabTitle);
                                                $stmt_fetch_data->execute();
                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                // Get the assessment title, total item value, and datetime
                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                $assessmentTitle = $row['assesment_title'];
                                                $totalItemValue = $row['total_item'];
                                                $datetime = $row['datetime'];

                                                // Fetch the score from the database
                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $finallabTitle);
                                                $stmt_fetch_score->execute();
                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                // Check if there are updated scores from the form submission
                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$finallabTitle]) ? $_POST['scores'][$student['student_id']][$finallabTitle] : $scoreValue;

                                                echo "<input type='text' name='scores[{$student['student_id']}][$finallabTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                    <?php endif; ?>

                                    <?php if (!empty($finaltermLaboratoryAssessmentTitles1)): ?>
                                        <?php foreach ($finaltermLaboratoryAssessmentTitles1 as $finallabTitle1): ?>
                                            <td>
                                                <?php
                                                // Fetch the assessment title for the specific assessment ID
                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                $stmt_fetch_data->bind_param("s", $finallabTitle1);
                                                $stmt_fetch_data->execute();
                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                // Get the assessment title, total item value, and datetime
                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                $assessmentTitle = $row['assesment_title'];
                                                $totalItemValue = $row['total_item'];
                                                $datetime = $row['datetime'];

                                                // Fetch the score from the database
                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $finallabTitle1);
                                                $stmt_fetch_score->execute();
                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                // Check if there are updated scores from the form submission
                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$finallabTitle1]) ? $_POST['scores'][$student['student_id']][$finallabTitle1] : $scoreValue;

                                                echo "<input type='text' name='scores[{$student['student_id']}][$finallabTitle1]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                    <?php endif; ?>


                                    <?php if (!empty($finaltermAssignmentAssessmentTitles)): ?>
                                        <?php foreach ($finaltermAssignmentAssessmentTitles as $finalassignmentTitle): ?>
                                            <td>
                                                <?php
                                                // Fetch the assessment title for the specific assessment ID
                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                $stmt_fetch_data->bind_param("s", $finalassignmentTitle);
                                                $stmt_fetch_data->execute();
                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                // Get the assessment title, total item value, and datetime
                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                $assessmentTitle = $row['assesment_title'];
                                                $totalItemValue = $row['total_item'];
                                                $datetime = $row['datetime'];

                                                // Fetch the score from the database
                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $finalassignmentTitle);
                                                $stmt_fetch_score->execute();
                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                // Check if there are updated scores from the form submission
                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$finalassignmentTitle]) ? $_POST['scores'][$student['student_id']][$finalassignmentTitle] : $scoreValue;

                                                echo "<input type='text' name='scores[{$student['student_id']}][$finalassignmentTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                    <?php endif; ?>
                                                


                                      <?php if (!empty($finaltermParticipationAssessmentTitles)): ?>
                                        <?php foreach ($finaltermParticipationAssessmentTitles as $finalparticipationTitle): ?>
                                            <td>
                                                <?php
                                                // Fetch the assessment title for the specific assessment ID
                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                $stmt_fetch_data->bind_param("s", $finalparticipationTitle);
                                                $stmt_fetch_data->execute();
                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                // Get the assessment title, total item value, and datetime
                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                $assessmentTitle = $row['assesment_title'];
                                                $totalItemValue = $row['total_item'];
                                                $datetime = $row['datetime'];

                                                // Fetch the score from the database
                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $finalparticipationTitle);
                                                $stmt_fetch_score->execute();
                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                // Check if there are updated scores from the form submission
                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$finalparticipationTitle]) ? $_POST['scores'][$student['student_id']][$finalparticipationTitle] : $scoreValue;

                                                echo "<input type='text' name='scores[{$student['student_id']}][$finalparticipationTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                    <?php endif; ?>



                                      <?php if (!empty($finaltermBehaviorAssessmentTitles)): ?>
                                        <?php foreach ($finaltermBehaviorAssessmentTitles as $finalbehaviorTitle): ?>
                                            <td>
                                                <?php
                                                // Fetch the assessment title for the specific assessment ID
                                                $sql_fetch_data = "SELECT assesment_title, total_item, datetime FROM tblassesment WHERE assesment_id = ?";
                                                $stmt_fetch_data = $connection->prepare($sql_fetch_data);
                                                $stmt_fetch_data->bind_param("s", $finalbehaviorTitle);
                                                $stmt_fetch_data->execute();
                                                $result_fetch_data = $stmt_fetch_data->get_result();
                                                // Get the assessment title, total item value, and datetime
                                                $row = ($result_fetch_data->num_rows > 0) ? $result_fetch_data->fetch_assoc() : ['assesment_title' => '', 'total_item' => 0, 'datetime' => ''];
                                                $assessmentTitle = $row['assesment_title'];
                                                $totalItemValue = $row['total_item'];
                                                $datetime = $row['datetime'];

                                                // Fetch the score from the database
                                                $sql_fetch_score = "SELECT score FROM tblstdassesment WHERE student_id = ? AND assesment_id = ?";
                                                $stmt_fetch_score = $connection->prepare($sql_fetch_score);
                                                $stmt_fetch_score->bind_param("ss", $student['student_id'], $finalbehaviorTitle);
                                                $stmt_fetch_score->execute();
                                                $result_fetch_score = $stmt_fetch_score->get_result();

                                                // Check if there are saved scores, if yes, use them; otherwise, set to 0
                                                $scoreValue = ($result_fetch_score->num_rows > 0) ? $result_fetch_score->fetch_assoc()['score'] : 0;

                                                // Check if there are updated scores from the form submission
                                                $scoreValue = isset($_POST['scores'][$student['student_id']][$finalbehaviorTitle]) ? $_POST['scores'][$student['student_id']][$finalbehaviorTitle] : $scoreValue;

                                                echo "<input type='text' name='scores[{$student['student_id']}][$finalbehaviorTitle]' value='$scoreValue' class='form-control text-center' oninput='validateScore(this, $totalItemValue)' />";
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <td></td> <!-- Display an empty cell if no participation assessments -->
                                    <?php endif; ?>


                                                    </tr>
                                                    <?php
                                                // Increment the counter for the next student
                                                $studentCounter++;
                                                ?>
                                                <?php endforeach; ?>
                            </tbody>
        </table>
                                            <!-- Save button -->
                                            <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mt-2">SAVE SCORE</button>
                                            </div>
                                            </form>