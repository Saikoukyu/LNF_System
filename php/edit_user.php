<?php
include("../php/connect.php");

// Check if the form data was posted
if (isset($_POST['originalEmail'], $_POST['newName'], $_POST['newEmail'], $_POST['newPassword'])) {
    
    // Get the posted data and trim any unnecessary spaces
    $originalEmail = trim($_POST['originalEmail']);
    $newName = trim($_POST['newName']);
    $newEmail = trim($_POST['newEmail']);
    $newPassword = $_POST['newPassword'];

    // Validate input fields to ensure they are not empty
    if (empty($originalEmail) || empty($newName) || empty($newEmail) || empty($newPassword)) {
        echo "All fields are required.";
        exit();
    }

    // Validate the email format
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Hash the new password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // SQL query to update user details
    $sql = "UPDATE tbl_do_admin SET username = ?, email = ?, password = ? WHERE email = ?";

    // Prepare the SQL statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters (s = string)
        $stmt->bind_param("ssss", $newName, $newEmail, $hashedPassword, $originalEmail);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "User details updated successfully!";
            } else {
                echo "No matching user found with the provided original email.";
            }
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid input. Please provide all required data.";
}
?>
