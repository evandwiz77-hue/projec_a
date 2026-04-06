<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "project_a");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields must be filled!";
    } else if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {

        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Error during registration: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; margin-top: 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #008CBA; text-decoration: none; }
    </style>
</head>
<body>

<h2>Register</h2>

<?php if ($error != ""): ?>
    <p style="color:red;"><strong>Error:</strong> <?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success != ""): ?>
    <p style="color:green;"><strong>Success:</strong> <?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required><br>

    <button type="submit" name="register">Register</button>
</form>

<div class="link">
    Already have an account? <a href="login.php">Login here</a>
</div>

</body>
</html>