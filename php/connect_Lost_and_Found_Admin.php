<?php
include('connect.php');

// Fetch form data
$firstName = $_POST['fn_firstname'];
$lastName = $_POST['ln_lastname'];
$item_founder_email = $_POST['item_founder_email'];
$item_founder_stud_id = $_POST['item_founder_stud_id'];
$item_type_id = $_POST['item_type_id'];
$item_name_id = $_POST['item_name_id'];
$item_detailed_name = $_POST['item_detailed_name'];
$item_brand = $_POST['item_brand'];
$item_location_id = $_POST['item_location_id'];
$item_specific_location_id = $_POST['item_specific_location_id'];
$date_lost = $_POST['date_lost'];
$time_lost = $_POST['time_lost'];
$item_add_info = $_POST['item_add_info'];
$item_status_id = '1';

// Handle file upload
$file_name = $_FILES['item_req_photo']['name'];
$temp_name = $_FILES['item_req_photo']['tmp_name'];
$upload_directory = '../html/item-images/'; // Folder to store images
$file_path = $upload_directory . basename($file_name);

// Move file to the server directory
if (move_uploaded_file($temp_name, $file_path)) {
    echo "File uploaded successfully.<br>";
} else {
    echo "Failed to upload image.<br>";
    exit; // Stop script execution if the file fails to upload
}

// Insert into tbl_full_name
$insertFullNameQuery = "INSERT INTO tbl_full_name (fn_firstname, fn_lastname) VALUES (?, ?)";
$stmt = $conn->prepare($insertFullNameQuery);
$stmt->bind_param("ss", $firstName, $lastName);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Full name successfully inserted into tbl_full_name.<br>";
        $fn_id = $conn->insert_id; // Get inserted fn_id
    } else {
        echo "Error inserting full name.<br>";
    }
} else {
    echo "Error in full name query: " . $stmt->error . "<br>";
}

// Insert into tbl_time_date
$insertTimeDateQuery = "INSERT INTO tbl_time_date (date_lost, time_lost) VALUES (?, ?)";
$stmt = $conn->prepare($insertTimeDateQuery);
$stmt->bind_param("ss", $date_lost, $time_lost);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Date and time successfully inserted into tbl_time_date.<br>";
        $time_date_id = $conn->insert_id; // Get inserted time_date_id
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
$stmt->close();
$conn->close();
?>
