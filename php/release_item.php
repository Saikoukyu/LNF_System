<?php
include("connect.php");

// Check if it's an AJAX request and the inquiry ID is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_id'])) {
    // Get the inquiry_id and item_req_name_id from the POST data
    $inquiry_id = $_POST['inquiry_id'];
    $item_id = $_POST['item_req_name_id'];

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete the inquiry from tbl_inquiry
        $deleteInquiryQuery = "DELETE FROM tbl_inquiry WHERE inquiry_id = ?";
        $stmtDeleteInquiry = $conn->prepare($deleteInquiryQuery);
        $stmtDeleteInquiry->bind_param("i", $inquiry_id);
        $stmtDeleteInquiry->execute();

        // Delete the related request from tbl_item_request
        $deleteItemRequestQuery = "DELETE FROM tbl_item_request WHERE item_req_name_id = ?";
        $stmtDeleteItemRequest = $conn->prepare($deleteItemRequestQuery);
        $stmtDeleteItemRequest->bind_param("i", $item_id);
        $stmtDeleteItemRequest->execute();

        // Update the item_status_id to 2 (Claimed) in tbl_item_description
        $updateItemStatusQuery = "UPDATE tbl_item_description SET item_status_id = 2 WHERE item_id = ?";
        $stmtUpdateItemStatus = $conn->prepare($updateItemStatusQuery);
        $stmtUpdateItemStatus->bind_param("i", $item_id);
        $stmtUpdateItemStatus->execute();



        // Commit the transaction
        mysqli_commit($conn);

        // Send a success response back to the AJAX request
        echo json_encode([
            "status" => "success",
            "message" => "Inquiry and related item request have been deleted, and the item status has been updated to Claimed.",
            "message" => "item id = " . $item_id
        ]);

    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        mysqli_rollback($conn);

        // Send an error response back to the AJAX request
        echo json_encode([
            "status" => "error",
            "message" => "An error occurred: " . $e->getMessage()
        ]);
    }

    // Close the statement and connection
    $stmtDeleteInquiry->close();
    $stmtDeleteItemRequest->close();
    $stmtUpdateItemStatus->close();
    $conn->close();
}
?>
