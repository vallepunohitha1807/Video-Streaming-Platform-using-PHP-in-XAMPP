<?php
session_start(); // Ensure session is started before using session variables

// Database connection
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "stream"; // Ensure this matches your DB name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Email or phone number
    $password = $_POST['password'];

    if ($username === "admin@gmail.com" && $password === "ADMIN") {
        $_SESSION['admin_logged_in'] = true; // Corrected session variable
        $_SESSION['admin_email'] = $username; // Store admin email
        echo "<script>alert('Admin Login Successful!'); window.location.href='admin.php';</script>";
        exit();
    }
    

    // Query to check user
    $sql = "SELECT id, fullname, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fullname, $email, $hashedPassword);
        $stmt->fetch();

        // Verify Password
        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION['user_id'] = $id;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['user_email'] = $email; // Store actual email instead of username input

            echo "<script>alert('Login successful!'); window.location.href='home.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('No account found with this email/phone number!');</script>";
    }

    $stmt->close();
}
$conn->close();
?>


<?php
$image_path = 'img/img1.jpeg';
if (!file_exists($image_path)) {
    // Use a fallback image or show an error message
    $image_path = 'img/login.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Naturals</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
/* General Reset */
* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Serif', Georgia;
}

/* Body with Background Image */
body {
    position: relative;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* Background Blur Effect */
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('<?php echo $image_path; ?>') no-repeat center center/cover;
    filter: blur(4px); /* Adjust blur intensity */
    z-index: -1; /* Keeps it behind the content */
}

/* Form Styling */
form {
    width: 420px;
    background: rgba(255, 255, 255, 0.15);
    padding: 35px 30px;
    border-radius: 15px;
    backdrop-filter: blur(20px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    text-align: center;
    z-index: 1;
}

/* Title */
h3 {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 18px;
}

/* Input Fields */
input {
    display: block;
    width: 100%;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 12px;
    font-size: 16px;
    color: white;
    margin-bottom: 12px;
    outline: none;
    transition: all 0.3s ease;
}

input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

input:focus {
    border-color: #4dd4ff;
    background: rgba(255, 255, 255, 0.3);
}

/* Button Styling */
button {
    width: 100%;
    background: linear-gradient(135deg, #3D405B, #3B82F6);
    color: white;
    padding: 14px;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(135deg, #3B82F6, #3D405B);
    transform: scale(1.05);
}

/* Signup Link */
.signup-link {
    margin-top: 12px;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.8);
}

.signup-link a {
    color: #4dd4ff;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
}

.signup-link a:hover {
    color: #ffffff;
}

/* Admin Button */
.admin-button {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #ff914d, #3D405B);
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.admin-button:hover {
    background: linear-gradient(135deg, #3D405B, #ff914d);
    transform: scale(1.1);
}

    </style>
</head>
<body>
    <form method="POST">
        <a href="registration.php" class="admin-button">Sign Up</a>
        <h3>User Login</h3>

        <input type="text" name="username" placeholder="Email or Phone" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>

    </form>
</body>
</html>
