<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "project_a");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username and Password must be filled!";
    } else {

        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Wrong username or password!";
            }
        } else {
            $error = "Wrong username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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

<h2>Login</h2>

<?php if ($error != ""): ?>
    <p style="color:red;"><strong>Error:</strong> <?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success != ""): ?>
    <p style="color:green;"><strong>Success:</strong> <?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit" name="login">Login</button>
</form>

<div class="link">
    Don't have an account? <a href="register.php">Register here</a>
</div>

</body>
</html>