
<?php
session_start();
include('dbcon.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind parameters
    $stmt = $con->prepare("SELECT * FROM tbluser WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Username exists, now verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            // Check the user_type and redirect accordingly
            if ($row['user_type'] == 'admin') {
                header('location: adminhome.php');
            } else {
                header('location: home.php');
            }
        } else {
            $login_error = 'Invalid Username and Password Combination';
        }
    } else {
        $login_error = 'Invalid Username and Password Combination';
    }
    $stmt->close();
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
    </style>
</head>
<body>
    <div class="form-wrapper">
        <form action="#" method="post">
            <h2 style="text-align:center; color: #333;">Login</h2>
            <?php
            if (isset($login_error)) {
                echo '<div class="error-message">' . $login_error . '</div>';
            }
            ?>
            <div class="form-item">
                <label class="label">Username</label>
                <br>
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="form-item">
                <label class="label">Password</label>
                <br>
                <input type="password" name="password" id="password" required placeholder="Password">
                <!-- Show/Hide Password button -->
                <button type="button" onclick="togglePasswordVisibility()">Show Password</button>
            </div>

            <div class="button-panel">
                <input type="submit" class="button" title="Log In" name="login" value="Login">
            </div>
        </form>
        <p><a href="forgotpassword.php" class="account" style="color: red;">Forgot Password? Click Here!</a></p>
        <p><a href="signup.php" class="account">Create New Account</a></p>
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