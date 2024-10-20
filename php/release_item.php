<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_id'])) {
    $inquiry_id = $_POST['inquiry_id'];
    $item_req_id = $_POST['item_req_id'];
    $item_id = $_POST['item_id'];
    $owner_name = $_POST['owner_name'];
    $owner_email = $_POST['owner_email'];
    $return_date = $_POST['return_date'];

    mysqli_begin_transaction($conn);

    try {
        // Delete inquiry
        $deleteInquiryQuery = "DELETE FROM tbl_inquiry WHERE inquiry_id = ?";
        $stmtDeleteInquiry = $conn->prepare($deleteInquiryQuery);
        $stmtDeleteInquiry->bind_param("i", $inquiry_id);
        $stmtDeleteInquiry->execute();

        // Delete item request
        $deleteItemRequestQuery = "DELETE FROM tbl_item_request WHERE item_req_id = ?";
        $stmtDeleteItemRequest = $conn->prepare($deleteItemRequestQuery);
        $stmtDeleteItemRequest->bind_param("i", $item_req_id);
        $stmtDeleteItemRequest->execute();

        // Update the item status and owner details
        $updateItemQuery = "
            UPDATE tbl_item_description 
            SET item_status_id = 2, owner_name = ?, owner_email = ?, return_date = ? 
            WHERE item_id = ?
        ";
        $stmtUpdateItem = $conn->prepare($updateItemQuery);
        $stmtUpdateItem->bind_param("sssi", $owner_name, $owner_email, $return_date, $item_id);
        $stmtUpdateItem->execute();

        if ($stmtUpdateItem->affected_rows === 0) {
            throw new Exception("Failed to update item status and owner details for item_id: $item_id.");
        }

        mysqli_commit($conn);

        // Send success response
        echo json_encode([
            "status" => "success",
            "message" => "Item released successfully."
        ]);

    } catch (Exception $e) {
        mysqli_rollback($conn);

        echo json_encode([
            "status" => "error",
            "message" => "An error occurred: " . $e->getMessage()
        ]);
    }

    $stmtDeleteInquiry->close();
    $stmtDeleteItemRequest->close();
    $stmtUpdateItem->close();
    $conn->close();
}
?>
