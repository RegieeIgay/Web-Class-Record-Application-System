<?php
// Start the session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user_id from the session
$user_id = $_SESSION['user_id'];
?>

<?php
include 'sidebar.php';
include 'navbar.php';
include 'classes.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CLASSRECORD</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        /* Copy the styles from the first snippet here */
        * {
            font-family: Arial, sans-serif;
        }
        body{
            background-color: white;
            font-family: Arial, sans-serif;
            
        }
        .container{
            margin-top: 70px;
            margin-left: 242px;
        }
        .col-md-4 {
            margin-top: 20px;
            width: 30%;
            text-align: center;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow */
            transition: box-shadow 0.3s ease; /* Smooth transition for hover effect */
        }
        .col-md-4:hover {
            transform: scale(1.05); /* Hover effect: Increase size */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Box shadow on hover */
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .container {
                margin-left: 0;
                padding-left: 15px;
                padding-right: 15px;
            }
            .col-md-4 {
                width: 100%;
            }
        }
    </style>
</head>
<body class="sb-nav-fixed">
</body>
</html>