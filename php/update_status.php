<?php
include("../php/connect.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if both item_id and status_id are present in the POST request
    if (isset($_POST['item_id']) && isset($_POST['status_id'])) {
        $itemId = $_POST['item_id'];
        $statusId = $_POST['status_id'];

        // Ensure these values are valid integers
        if (!filter_var($itemId, FILTER_VALIDATE_INT) || !filter_var($statusId, FILTER_VALIDATE_INT)) {
            echo "Invalid item ID or status ID.";
            exit;
        }

        try {
            // Prepare the SQL query to update the status
            $sql = "UPDATE tbl_item_description SET item_status_id = :status_id WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);

            // Use execute with an array to bind parameters
            $stmt->execute([
                ':status_id' => $statusId,
                ':item_id' => $itemId
            ]);

            
        } catch (PDOException $e) {
            // Display any error for debugging
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request. Item ID or Status ID missing.";
    }
} else {
    echo "Invalid request method. Only POST allowed.";
}
?>
