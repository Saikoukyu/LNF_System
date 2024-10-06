<?php
include("../php/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username from the POST request
    $email = $_POST['email'];

    // Prepare the delete statement
    $sql = "DELETE FROM tbl_do_admin WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind the email to the statement
    $stmt->bind_param("s", $email);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
