<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// DB CONNECTION
$conn = mysqli_connect("localhost", "root", "", "project_a");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

// HANDLE UPLOAD
if (isset($_POST['upload'])) {

    $title = $_POST['title'];
    $date = $_POST['date'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $folder = "uploads/" . $file;

    if (move_uploaded_file($tmp, $folder)) {

        mysqli_query($conn, "INSERT INTO uploads (title, file_name, upload_date)
                             VALUES ('$title', '$file', '$date')");

        $message = "Upload successful!";
    } else {
        $message = "Upload failed!";
    }
}

// FETCH DATA
$result = mysqli_query($conn, "SELECT * FROM uploads ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card-custom {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 25px;
            margin-bottom: 20px;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        .logout-link {
            color: white !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <div class="ms-auto">
            <a class="nav-link logout-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container dashboard-container">

    <!-- Upload Form -->
    <div class="card-custom">
        <h4>Upload File</h4>

        <?php if ($message != ""): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>File</label>
                <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png" required>
            </div>

            <button type="submit" name="upload" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card-custom">
        <h4>Uploaded Data</h4>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td>
                            <a href="uploads/<?php echo $row['file_name']; ?>" target="_blank">
                                View File
                            </a>
                        </td>
                        <td><?php echo $row['upload_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>