<?php
include("../php/connect.php");

// Check if the form data was posted
if (isset($_POST['originalEmail']) && isset($_POST['newName']) && isset($_POST['newEmail']) && isset($_POST['newPassword'])) {
    
    // Get the posted data
    $originalEmail = $_POST['originalEmail'];
    $newName = $_POST['newName'];
    $newEmail = $_POST['newEmail'];
    $newPassword = $_POST['newPassword'];

    // Create the SQL query to update the user details
    $sql = "UPDATE tbl_do_admin SET username = ?, email = ?, password = ? WHERE email = ?";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters (s = string)
        $stmt->bind_param("ssss", $newName, $newEmail, $newPassword, $originalEmail);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "User details updated successfully!";
        } else {
            echo "Error updating record: " . $conn->error;
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
