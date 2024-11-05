<?php
include("connect2.php");

if (!$conn) {
    echo "Database connection failed.";
} else {
    echo "Database connected successfully.";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if item_id is present in the POST request
    if (isset($_POST['item_id'])) {
        $itemId = $_POST['item_id'];

        // Ensure itemId is a valid integer
        if (!filter_var($itemId, FILTER_VALIDATE_INT)) {
            echo "Invalid item ID.";
            exit;
        }

        try {
            // Ensure the SQL query is assigned to $sql
            $sql = " DELETE tbl_item_description, tbl_full_name
    FROM tbl_item_description
    INNER JOIN tbl_full_name ON tbl_full_name.fn_id = tbl_item_description.item_full_name_id
    WHERE tbl_item_description.item_id = :item_id; 
    ";
            $stmt = $conn->prepare($sql);

          

            // Execute the query with the provided item_id
            if ($stmt->execute([':item_id' => $itemId])) {
                echo "Item deleted successfully!";
            } else {
                echo "Failed to delete item.";
            }

        } catch (PDOException $e) {
            // Display any error for debugging
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request. Item ID missing.";
    }
} else {
    echo "Invalid request method. Only POST allowed.";
}
?>
