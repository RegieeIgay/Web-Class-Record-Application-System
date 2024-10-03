<?php include 'adminsidebar.php'; ?>
<?php include 'adminnavbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Image Upload</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4;
            margin: 0;
        }

        .form-container {
            width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "classrecord";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
?>

<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h2>User Image Upload</h2>
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" required>
        <br>
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageType = $image['type'];
        $imageData = addslashes(file_get_contents($image['tmp_name']));

        // Check if the user ID already exists in the images table
        $checkUserQuery = "SELECT * FROM images WHERE user_id = '$user_id'";
        $checkUserResult = $conn->query($checkUserQuery);

        if ($checkUserResult->num_rows > 0) {
            // If user ID exists, update the existing record
            $sql = "UPDATE images 
                    SET image_name = '$imageName', image_type = '$imageType', image_data = '$imageData'
                    WHERE user_id = '$user_id'";
        } else {
            // If user ID doesn't exist, insert a new record
            $sql = "INSERT INTO images (user_id, image_name, image_type, image_data) 
                    VALUES ('$user_id', '$imageName', '$imageType', '$imageData')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "Image succesfully added for user  $user_id.";
        } else {
            echo "Error updating/adding image: " . $conn->error;
        }
    }

    $conn->close();
    ?>
</div>

</body>
</html>
