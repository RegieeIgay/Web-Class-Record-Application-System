<?php
session_start();
include('dbcon.php');

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $Fullname = $_POST['Fullname']; // New: Get fullname from form
    $Department = $_POST['Department']; // New: Get department from form

    // Validate username length
    if (strlen($username) < 5) {
        $error_message = 'Username must be at least 5 characters long.';
    } elseif (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) {
        // Validate password to contain both letters and numbers
        $error_message = 'Password must contain both letters and numbers.';
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate a unique user ID
        $user_id = uniqid();
    
        // Determine the user type based on existing users count
        $checkUsersQuery = "SELECT COUNT(*) as user_count FROM tbluser";
        $checkResult = mysqli_query($con, $checkUsersQuery);
        $userData = mysqli_fetch_assoc($checkResult);
        $userCount = $userData['user_count'];
    
        // If no users exist, set the first user as 'admin', otherwise 'user'
        $userType = ($userCount == 0) ? 'admin' : 'user';
    
        // Use the hashed password and determined user type in the SQL query
        $query = "INSERT INTO tbluser (user_id, username, password, user_type, Fullname, Department) 
                  VALUES ('$user_id', '$username', '$hashed_password', '$userType', '$Fullname', '$Department')";
        $result = mysqli_query($con, $query);
    
        // Check if the signup was successful
        if ($result) {
            // Set session variable for success message
            $_SESSION['success_message'] = 'Account created successfully!';
            header("Location: signup.php");
            exit();
        } else {
            $error_message = 'Error creating account. Please try again.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-W3C0hjPFNI0j+8W2OwYxwLFqY8pMzsXSFdLzS4ze9wBdWqnGBTRpuCXp2M5A42zHlL4vDuj52j7Y+rzMl51jJw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-image: url('cpsubuilding.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .error-message, .success-message {
            color: #D8000C;
            background-color: #FFD2D2;
            border: 1px solid #D8000C;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success-message {
            color: #4F8A10;
            background-color: #DFF2BF;
            border: 1px solid #4F8A10;
        }
        .form-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 20px;
            width: 350px;
            color: #333;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.5s ease forwards;
            text-align: center;
        }
        .form-item {
            margin-bottom: 20px;
        }
        .form-item input {
            width: calc(100% - 28px);
            padding: 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2), 
                        -5px -5px 10px rgba(255, 255, 255, 0.5);
        }
        .form-item input:focus {
            outline: none;
            box-shadow: inset 3px 3px 5px rgba(0, 0, 0, 0.1), 
                        inset -3px -3px 5px rgba(255, 255, 255, 0.5);
        }
        .button-panel {
            text-align: center;
        }
        .button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #218838;
        }
        .account {
            text-align: center;
            display: block;
            margin-top: 15px;
            color: #333;
        }
        .password-toggle-btn {
            background-color: #fff;
            border: none;
            color: #ccc;
            cursor: pointer;
            outline: none;
            padding: 5px 10px;
            border-radius: 0 5px 5px 0;
            transition: color 0.3s ease;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 5px;
            z-index: 1;
            display: block;
        }
        .password-toggle-btn:hover {
            color: #218838;
        }
        .fa-eye, .fa-eye-slash {
            font-size: 18px;
            color: #333;
        }
        @media (max-width: 768px) {
            body {
                background-size: 100% 100%;
            }
            .form-wrapper {
                width: 90%;
                padding: 15px;
            }
            .form-item input, .button {
                padding: 8px;
            }
            .form-item {
                margin-bottom: 15px;
            }
        }
        .select-input {
    width: calc(100% - 28px);
    padding: 10px;
    box-sizing: border-box;
    border: none;
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2), 
                -5px -5px 10px rgba(255, 255, 255, 0.5);
}

.select-input:focus {
    outline: none;
    box-shadow: inset 3px 3px 5px rgba(0, 0, 0, 0.1), 
                inset -3px -3px 5px rgba(255, 255, 255, 0.5);
}

.password-toggle-btn {
            background-color: #fff;
            border: none;
            color: #ccc;
            cursor: pointer;
            outline: none;
            padding: 5px 10px;
            border-radius: 0 5px 5px 0;
            transition: color 0.3s ease;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 5px;
            z-index: 1;
            display: block;
        }
        .password-toggle-btn:hover {
            color: #218838;
        }
        .fa-eye, .fa-eye-slash {
            font-size: 18px;
            color: #333;
        }

    </style>
</head>
<body>
    <div class="form-wrapper">
        <form action="#" method="post">
        <?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
    <?php unset($_SESSION['success_message']); ?> <!-- Clear the session variable after displaying -->
<?php endif; ?>

            <h2 style="text-align:center; color: #333;">Create New Account</h2>
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php elseif (isset($success_message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <div class="form-item">
                <label class="label">Fullname</label>
                <br>
                <input type="text" name="Fullname" required placeholder="Fullname">
            </div>

 <div class="form-item">
    <label class="label">Department</label><br>
    <select name="Department" required class="select-input">
        <option value="" disabled selected>Select Department</option>
        <?php
        // Fetch departments from tbl_course
        $department_query = "SELECT course_title FROM tbl_course";
        $department_result = mysqli_query($con, $department_query);
        
        // Check if there are departments available
        if (mysqli_num_rows($department_result) > 0) {
            // Loop through each department and create an option element
            while ($row = mysqli_fetch_assoc($department_result)) {
                echo "<option value='" . $row['course_title'] . "'>" . $row['course_title'] . "</option>";
            }
        } else {
            // No departments found
            echo "<option disabled>No departments available</option>";
        }
        ?>
    </select>
</div>

            <div class="form-item">
                <label class="label">Username</label>
                <br>
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="form-item">
                <label class="label">Password</label>
                <br>
                <input type="password" name="password" id="password" required placeholder="Password">
                <!-- Show/Hide Password button --><br>
                <button type="button" onclick="togglePasswordVisibility()">Show Password</button>
            </div>

            
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" name="signup" value="Sign Up">
            </div>
        </form>
      

       
        <p><a href="login.php" class="account">Already have an account? Login here</a></p>
        <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var toggleButton = document.querySelector('.form-item button');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.textContent = "Hide Password";
        } else {
            passwordInput.type = "password";
            toggleButton.textContent = "Show Password";
        }
    }
</script>
</body>
</html>