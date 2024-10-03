<?php
// Assuming you have a database connection established
include 'config.php'; 


// Initialize $class_id
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

// Check if the form is submitted and the 'save' key is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    // Collect form data
    $title = $_POST['title'];
    $date = $_POST['date'];

    // Convert 12-hour time to 24-hour format
    $time_12_hour = $_POST['time'];
    $time_24_hour = date("H:i", strtotime($time_12_hour));

    $location = $_POST['location'];
    $description = $_POST['description'];
    
    // Insert data into tblschedule
    $sql = "INSERT INTO tblschedule (title, date, time, location, description, class_id) 
            VALUES ('$title', '$date', '$time_24_hour', '$location', '$description', '$class_id')";

    if ($connection->query($sql) === TRUE) {
        echo "Schedule added successfully";

        // Redirect to view schedule page after successful addition
        header("Location: viewallschedule.php?class_id=$class_id");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    // Close the database connection
    $connection->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
    <style>
            * {
                font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            
          
        }

        .container {
           width:100%;
            text-align: center;
            padding:20px;
            margin-bottom: 20px;
            
        }

        h2 {
            text-align: center;
          
        }

        form {
            background-color: #fff;
            border-radius: 8px;
           
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 60px;
            margin-left: 200px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
          
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<?php include 'navbar.php'; ?>

<div class="container">
    <form action='addschedule.php?class_id=<?php echo $class_id; ?>' method='post'>
        <h2>Add Schedule</h2>

        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="date">Date:</label>
        <input type="date" name="date" required>

        <label for="time">Time:</label>
        <input type="time" name="time" pattern="(0[1-9]|1[0-2]):[0-5][0-9] [APap][mM]" title="Please enter a valid time in 12-hour format" required>

        <label for="location">Location:</label>
        <input type="text" name="location" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <!-- Display the class_id from the URL parameter in a hidden field -->
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">

        <button type='submit' name='save'>Submit</button>
    </form>
</div>

</body>
</html>
