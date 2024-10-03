<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPSU HINIGARAN CLASS RECORD</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-image: url('cpsubuilding.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: background-color 0.5s, color 0.5s;
            color: #fff; /* Changed text color to white for better contrast */
            height: 100vh;
            margin: 0;
            width: 100%; /* Added width property */
            position: relative; /* Added position property */
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Added semi-transparent black overlay */
            z-index: 1; /* Ensure the overlay stays on top of the background image */
        }
        h1 {
            font-size: calc(2rem + 3vw); /* Adjusted font size for better responsiveness */
            margin: 0;
            opacity: 0.8;
            transition: all 1s ease-out;
            cursor: pointer;
            color: #ffd700; /* Changed text color to yellow */
            text-align: center; /* Centered text */
            z-index: 2; /* Ensure the h1 stays on top of the overlay */
            position: relative; /* Added position property */
        }
        p {
            margin: 20px 0;
            font-size: calc(0.8rem + 0.5vw);
            z-index: 2; /* Ensure the buttons stay on top of the overlay */
            position: relative; /* Added position property */
        }
        .login-btn, .signup-btn {
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin: 0.5rem;
            transition: background-color 0.3s, transform 0.3s, color 0.3s;
            color: #fff; /* Changed text color to white */
            background-color: #4a90e2; /* Changed background color to blue */
            border: 2px solid transparent;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: inline-block; /* Added display property */
            text-align: center; /* Centered text */
            line-height: 1.5; /* Adjusted line height */
            font-weight: bold; /* Added font weight for emphasis */
            text-transform: uppercase; /* Converted text to uppercase */
            letter-spacing: 1px; /* Added letter spacing for readability */
            z-index: 2; /* Ensure the buttons stay on top of the overlay */
            position: relative; /* Added position property */
        }
        .login-btn:hover, .signup-btn:hover {
            background-color: #007bff; /* Changed hover background color to darker blue */
        }
        .toggle-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            font-size: 28px;
            z-index: 2; /* Ensure the toggle button stays on top of the overlay */
            color: #ffd700;
        }
        .btn-container {
            margin-top: 30px; /* Added margin for better spacing */
        }
        @media (max-width: 768px) {
            h1 { font-size: calc(1.5rem + 2vw); /* Adjusted font size for smaller screens */ }
            p { font-size: calc(0.7rem + 1vw); }
            .login-btn, .signup-btn {
                padding: 0.5rem 1rem; /* Adjusted button padding for smaller screens */
            }
        }
    </style>
</head>
<body class="dark-mode d-flex justify-content-center align-items-center">
    <div class="overlay"></div> <!-- Added overlay -->
    <div class="text-center">
        <h1>CPSU HINIGARAN CLASS RECORD</h1>
        <div class="btn-container"> <!-- Added container for buttons -->
            <p>
                <a class="signup-btn btn" href="signup.php"><i class="fas fa-user-plus"></i> Create Account</a>
                <a class="login-btn btn" href="login.php"><i class="fas fa-sign-in-alt"></i> Log In</a>
            </p>
        </div>
        <div class="toggle-btn" onclick="toggleMode()">🌞</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMode() {
            const body = document.body;
            body.classList.toggle('light-mode');
            body.classList.toggle('dark-mode');
            const toggleBtn = document.querySelector('.toggle-btn');
            toggleBtn.textContent = body.classList.contains('dark-mode') ? '🌞' : '🌙';
        }
        window.onload = function() {
            const heading = document.querySelector('h1');
            heading.style.opacity = 1;
        };
    </script>
</body>
</html>