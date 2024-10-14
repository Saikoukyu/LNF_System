<?php
session_start();  // Start session
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];  // Raw password from user input

    // Validate input to ensure it's not empty
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please provide both email and password.'); window.location.href = '../html/NU_LoginPage.php';</script>";
        exit();
    }

    // Prepare the SQL query to retrieve the user by email
    $query = "SELECT * FROM tbl_do_admin WHERE email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);  // Bind email parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user with the provided email exists
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify the hashed password
            if (password_verify($password, $row['password'])) {
                // Store session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];

                // Redirect to the admin dashboard
                header('Location: ../html/Admin_Dashboard.php');
                exit();
            } else {
                echo "<script>alert('Invalid email or password.'); window.location.href = '../html/NU_LoginPage.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href = '../html/NU_LoginPage.php';</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
