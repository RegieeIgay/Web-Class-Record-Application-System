<?php
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "classrecord";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    if(isset($_POST["username"]) && isset($_POST["new_password"])) {
        $username = $_POST["username"];
        $new_password = $_POST["new_password"];
        
        // Check if username exists in database
        $sql = "SELECT * FROM tbluser WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Store new password in database
            $sql = "UPDATE tbluser SET password = '$hashed_password' WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Username not found.";
        }
    }
}
?>

<!-- HTML Form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="username">Enter your username:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="new_password">Enter your new password:</label><br>
    <input type="password" id="new_password" name="new_password" required><br>
    <button type="submit">Submit</button>
</form>
