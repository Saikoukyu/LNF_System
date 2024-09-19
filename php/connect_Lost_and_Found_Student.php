<?php
include('connect.php');

$firstName = $_POST['fn_firstname'];
$lastName = $_POST['ln_lastname'];
$item_req_sender_email = $_POST['item_req_sender_email'];
$item_req_sender_stud_id = $_POST['item_req_sender_stud_id'];
$item_req_type_id = $_POST['item_req_type_id'];
$item_req_name_id = $_POST['item_req_name_id'];
$item_req_detailed_name = $_POST['item_req_detailed_name'];
$item_req_brand = $_POST['item_req_brand'];
$item_req_location_id = $_POST['item_req_location_id'];
$item_req_specific_location_id = $_POST['item_req_specific_location_id'];
$date_lost = $_POST['date_lost'];
$time_lost = $_POST['time_lost'];
$item_req_add_info = $_POST['item_req_add_info'];

$insertFullNameQuery = "INSERT INTO tbl_full_name (fn_firstname, fn_lastname) VALUES (?, ?)";
$stmt = $conn->prepare($insertFullNameQuery);
$stmt->bind_param("ss", $firstName, $lastName);

if ($stmt->execute()) {
    // Check if the insert was successful
    if ($stmt->affected_rows > 0) {
        echo "Full name successfully inserted into tbl_full_name.<br>";
        
        // Get the fn_id of the newly inserted row
        $fn_id = $conn->insert_id; // This is the fn_id for tbl_full_name
    } else {
        echo "Error inserting full name.<br>";
    }
} else {
    echo "Error in full name query: " . $stmt->error . "<br>";
}

$insertTimeDateQuery = "INSERT INTO tbl_time_date (date_lost, time_lost) VALUES (?, ?)";
$stmt = $conn->prepare($insertTimeDateQuery);
$stmt->bind_param("ss", $date_lost, $time_lost);

if ($stmt->execute()) {
    // Check if the insert was successful
    if ($stmt->affected_rows > 0) {
        echo "Date and time successfully inserted into tbl_time_date.<br>";
        
        // Get the time_date_id of the newly inserted row
        $time_date_id = $conn->insert_id; // This is the time_date_id for tbl_time_date
    } else {
        echo "Error inserting date and time.<br>";
    }
} else {
    echo "Error in date and time query: " . $stmt->error . "<br>";
}

if ($stmt->execute()) {
    // Step 2: Check if the insert was successful
    if ($stmt->affected_rows > 0) {

        // Step 4: Insert into tbl_item_request using the fn_id as the foreign key
        $insertItemRequestQuery = "INSERT INTO tbl_item_request (item_req_full_name_id, item_req_sender_email, item_req_sender_stud_id, item_req_type_id, item_req_name_id, item_req_detailed_name, item_req_brand, item_req_location_id, item_req_specific_location_id, item_req_time_date_id, item_req_add_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($insertItemRequestQuery);
        $stmt2->bind_param("issiissiiis", $fn_id, $item_req_sender_email, $item_req_sender_stud_id, $item_req_type_id, $item_req_name_id, $item_req_detailed_name, $item_req_brand, $item_req_location_id, $item_req_specific_location_id, $time_date_id, $item_req_add_info);

        if ($stmt2->execute()) {
            echo "Item request successfully inserted into tbl_item_request.";
        } else {
            echo "Error inserting item request: " . $stmt2->error;
        }

        $stmt2->close();
    } else {
        echo "Failed to insert full name into tbl_full_name.";
    }
} else {
    echo "Error inserting full name: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
