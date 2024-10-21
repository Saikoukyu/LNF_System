<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    // Get POST data
    $itemId = $_POST['item_id'];
    $ownerName = $_POST['owner_name'];
    $ownerEmail = $_POST['owner_email'];
    $returnDate = $_POST['return_date'];

    try {
        // Prepare SQL query to update the owner information in tbl_item_description
        $sql = "UPDATE tbl_item_description 
                SET owner_name = ?, owner_email = ?, return_date = ? 
                WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $ownerName, $ownerEmail, $returnDate, $itemId);

        if ($stmt->execute()) {
            // Return success response
            echo json_encode(["status" => "success", "message" => "Owner information updated successfully."]);
        } else {
            throw new Exception("Failed to update owner information.");
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
