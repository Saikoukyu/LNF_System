<?php
include("../php/connect.php");

// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href = '../html/manage_user.php';</script>";
        exit();
    }

    // Check if required fields are not empty
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href = '../html/manage_user.php';</script>";
        exit();
    }

    $role = 'Admin'; // Assign role

    // Check for duplicate username or email
    $checkSql = "SELECT * FROM tbl_do_admin WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Username or email already exists!'); window.location.href = '../html/manage_user.php';</script>";
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert user
    $sql = "INSERT INTO tbl_do_admin (username, email, password, role) VALUES (?, ?, ?, ?)";

    // Check if connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Check if the preparation was successful
    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!'); window.location.href = '../html/manage_user.php';</script>";
    } else {
        echo "<script>alert('Error adding user: " . $stmt->error . "'); window.location.href = '../html/manage_user.php';</script>";
    }

    // Close the statements and connection
    $checkStmt->close();
    $stmt->close();
    $conn->close();
}
?>
