<?php
include("../php/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalUsername = $_POST['originalUsername'];  // The original username
    $newUsername = $_POST['newUsername'];            // The new username
    $newPassword = $_POST['newPassword'];            // The new password

    // Prepare the SQL statement to update the user's details
    $sql = "UPDATE tbl_do_admin SET username = ?, password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind the new values to the statement
    $stmt->bind_param("sss", $newUsername, $newPassword, $originalUsername);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User updated successfully!";
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
