<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom styling for the cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Added margin for better spacing */
        }

        .card .card-header {
            background-color: #28a745;
            border-radius: 10px 10px 0 0;
        }

        .card .card-body {
            padding: 20px;
        }

        .card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .card .btn-primary {
            background-color: #007bff;
            border: none;
        }

        /* Custom styling for responsive design */
        @media (max-width: 992px) {
            .col-lg-4 {
                flex: 0 0 50%; /* Display 2 cards per row on medium screens */
                max-width: 50%;
            }
        }

        @media (max-width: 768px) {
            .col-lg-4 {
                flex: 0 0 100%; /* Display 1 card per row on small screens */
                max-width: 100%;
            }
        }

        /* Style for logout button */
        .logout-btn {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .logout-btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">User Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Settings</a>
            </li>
          </ul>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link logout-btn" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
    </nav>

    <div class="container mt-5 mb-5"> <!-- Added top and bottom margin -->
        <div class="row">
            <?php
            // PHP code to fetch data from the database and generate cards
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

            // Establish a connection to the database
            $result = mysqli_connect('localhost', 'root', '', 'classrecord');

            // Check if the connection was successful
            if (!$result) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query to fetch users with usertype 'user' along with their images
            $query = "SELECT tbluser.*, images.image_data, images.image_name, images.image_type FROM tbluser LEFT JOIN images ON tbluser.user_id = images.user_id WHERE tbluser.user_type = 'user'";

            $result1 = mysqli_query($result, $query);

            // Loop through the query results
            while ($row = mysqli_fetch_assoc($result1)) {
                echo '
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header text-white">
                            Department: ' . $row['Department'] . '
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Faculty: ' . $row['Fullname'] . '</h5>';

                // Display the image if available
                if ($row['image_data'] && $row['image_type']) {
                    $imageSrc = 'data:' . $row['image_type'] . ';base64,' . base64_encode($row['image_data']);
                    echo '<img src="' . $imageSrc . '" alt="User Image">';
                }

                echo '
                            <a href="classinfo.php?user_id=' . $row['user_id'] . '" class="btn btn-primary mt-3">View Profile</a>
                        </div>
                    </div>
                </div>';
            }

            // Close the database connection
            mysqli_close($result);
            ?>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>