<?php
include("../php/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the do_id from the POST request
    $do_id = $_POST['do_id'];

    // Prepare the delete statement
    $sql = "DELETE FROM tbl_do_admin WHERE do_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind the do_id to the statement
    $stmt->bind_param("i", $do_id);  // "i" for integer

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
