<?php

$servername = "localhost";
$username = "root";
$password = "";     
$dbname = "db_laf";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

$sql = "SELECT * FROM tbl_do_admin WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: Admin_Fashboard.html"); 
} else {
    echo "Invalid username or password";
}

$stmt->close();
$conn->close();
?>
