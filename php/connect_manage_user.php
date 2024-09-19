<?php
include("../php/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href = '../html/manage_user.php';</script>";
        exit();
    }

    $role = ('Admin');

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert user (only username and password)
    $sql = "INSERT INTO tbl_do_admin (username, password, role) VALUES (?, ?, ?)";

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Check if the preparation was successful
    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind the parameters (username and hashed password)
    $stmt->bind_param("sss", $username, $password, $role);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!'); window.location.href = '../html/manage_user.php';</script>";
    } else {
        echo "<script>alert('Error adding user: " . $stmt->error . "'); window.location.href = '../html/manage_user.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
