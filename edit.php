<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "project_a");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

$id = $_GET['id'];

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];

    mysqli_query($conn, "UPDATE uploads SET title='$title', upload_date='$date' WHERE id=$id");

    header("Location: edit.php?id=$id&message=Updated successfully!");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM uploads WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .edit-container {
            margin-top: 50px;
        }
        .card-custom {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 25px;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        .back-link {
            color: white !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Edit Upload</a>
        <div class="ms-auto">
            <a class="nav-link back-link" href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</nav>

<div class="container edit-container">

    <div class="card-custom">
        <h4>Edit Upload</h4>

        <?php if ($message != ""): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo $row['upload_date']; ?>" required>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
setTimeout(function() {
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        alertElement.style.display = 'none';
    }
}, 3000);
</script>
</body>
</html>