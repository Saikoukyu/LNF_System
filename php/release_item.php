<?php
include("connect.php");

// Check if it's an AJAX request and the inquiry ID is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_id'])) {
    // Get the inquiry_id and item_req_id from the POST data
    $inquiry_id = $_POST['inquiry_id'];
    $item_req_id = $_POST['item_req_id'];  // Changed from item_req_name_id to item_req_id
    $item_id = $_POST['item_id']; // Correct reference to item_id

    // DEBUG: Output incoming POST data for troubleshooting
    error_log("Received inquiry_id: " . $inquiry_id);
    error_log("Received item_req_id: " . $item_req_id);

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete the inquiry from tbl_inquiry
        $deleteInquiryQuery = "DELETE FROM tbl_inquiry WHERE inquiry_id = ?";
        $stmtDeleteInquiry = $conn->prepare($deleteInquiryQuery);
        $stmtDeleteInquiry->bind_param("i", $inquiry_id);
        $stmtDeleteInquiry->execute();

        // Check if the inquiry was successfully deleted
        if ($stmtDeleteInquiry->affected_rows === 0) {
            throw new Exception("Failed to delete the inquiry with ID: " . $inquiry_id);
        }

        

        // DEBUG: Check if the request exists before attempting to delete it
        $checkRequestQuery = "SELECT * FROM tbl_item_request WHERE item_req_id = ?";
        $stmtCheckRequest = $conn->prepare($checkRequestQuery);
        $stmtCheckRequest->bind_param("i", $item_req_id);
        $stmtCheckRequest->execute();
        $result = $stmtCheckRequest->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("No item request found with item_req_id: " . $item_req_id);
        }

        // Delete the related request from tbl_item_request using item_req_id
        $deleteItemRequestQuery = "DELETE FROM tbl_item_request WHERE item_req_id = ?";  // Updated query
        $stmtDeleteItemRequest = $conn->prepare($deleteItemRequestQuery);
        $stmtDeleteItemRequest->bind_param("i", $item_req_id);  // Changed parameter binding to item_req_id
        $stmtDeleteItemRequest->execute();

        // Check if the request was successfully deleted
        if ($stmtDeleteItemRequest->affected_rows === 0) {
            throw new Exception("Failed to delete the item request with ID: " . $item_req_id);
        }
  // Update the item status to '2' (Claimed) in tbl_item_description
  $updateItemStatusQuery = "UPDATE tbl_item_description SET item_status_id = 2 WHERE item_id = ?";
  $stmtUpdateItemStatus = $conn->prepare($updateItemStatusQuery);
  $stmtUpdateItemStatus->bind_param("i", $item_id);
  $stmtUpdateItemStatus->execute();

  if ($stmtUpdateItemStatus->affected_rows === 0) {
      throw new Exception("Failed to update item status for item_id: $item_id.");
  }

        // Commit the transaction
        mysqli_commit($conn);

        // Send a success response back to the AJAX request
        echo json_encode([
            "status" => "success",
            "message" => "Inquiry and related item request have been deleted, and the item status has been updated to Claimed.",
            "item_id" => $item_req_id
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