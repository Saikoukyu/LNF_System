<?php
include("connect.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        
        $query = "SELECT * FROM tbl_do_admin WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header('Location: ../html/Admin_Dashboard.php');
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href = '../html/NU_LoginPage.php';</script>";
        }

        $stmt->close();
    } else {
        echo "Please provide both username and password.";
    }
}
?>
