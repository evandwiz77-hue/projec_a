<?php
$conn = mysqli_connect("localhost", "root", "", "project_a");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>