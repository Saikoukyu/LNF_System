<?php
session_start();  // Start session
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Example query (make sure it's working)
    $query = "SELECT * FROM tbl_do_admin WHERE email = ? AND password = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo "User found in database: "; // Debugging line
            print_r($row);

            if ($password === $row['password']) {
                // Store session variables
            
                $_SESSION['username'] = $row ['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];

                echo "Session set successfully."; // Debugging line

                // Redirect after successful login
                header('Location: ../html/Admin_Dashboard.php');
                exit();
            }
        } else {
            echo "<script>alert('Invalid email or password'); window.location.href = '../html/NU_LoginPage.php';</script>";
        }

        $stmt->close();
    } else {
        echo "Please provide both email and password.";
    }
}
?>
