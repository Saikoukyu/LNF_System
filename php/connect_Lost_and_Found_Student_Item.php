<?php
include('../php/connect.php');

// Define the maximum file size in bytes (15 MB)
$maxFileSize = 15 * 1024 * 1024; // 15 MB

// Allowed image types
$allowedFileTypes = ['image/jpeg', 'image/jpg', 'image/png'];

// Fetch form data
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

// Capture the item_id from the form
$item_id = $_POST['item_id'];

// Handle file upload
$file_name = $_FILES['item_req_photo']['name'];
$temp_name = $_FILES['item_req_photo']['tmp_name'];
$file_size = $_FILES['item_req_photo']['size'];
$file_type = $_FILES['item_req_photo']['type'];
$upload_directory = '../html/item-images/'; // Folder to store images
$file_path = $upload_directory . basename($file_name);

// Validate file type
if (!in_array($file_type, $allowedFileTypes)) {
    die("Error: Only .jpg, .jpeg, and .png files are allowed.");
}

// Validate file size
if ($file_size > $maxFileSize) {
    die("Error: File size exceeds the 15MB limit.");
}

// Move file to the server directory
if (move_uploaded_file($temp_name, $file_path)) {
    echo "File uploaded successfully.<br>";
} else {
    die("Failed to upload image.<br>");
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
        die("Error inserting full name.<br>");
    }
} else {
    die("Error in full name query: " . $stmt->error . "<br>");
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
        die("Error inserting date and time.<br>");
    }
} else {
    die("Error in date and time query: " . $stmt->error . "<br>");
}

// Insert into tbl_item_request with image path
$insertItemRequestQuery = "INSERT INTO tbl_item_request (item_req_full_name_id, item_req_sender_email, item_req_sender_stud_id, item_req_type_id, item_req_name_id, item_req_detailed_name, item_req_brand, item_req_location_id, item_req_specific_location_id, item_req_time_date_id, item_req_add_info, item_req_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt2 = $conn->prepare($insertItemRequestQuery);
$stmt2->bind_param("issiissiiiss", $fn_id, $item_req_sender_email, $item_req_sender_stud_id, $item_req_type_id, $item_req_name_id, $item_req_detailed_name, $item_req_brand, $item_req_location_id, $item_req_specific_location_id, $time_date_id, $item_req_add_info, $file_path);

if ($stmt2->execute()) {
    echo "Item request successfully inserted into tbl_item_request.<br>";
    $item_req_id = $conn->insert_id; // Get the inserted item request ID
} else {
    die("Error inserting item request: " . $stmt2->error);
}

// Now insert into tbl_inquiry using the newly inserted item_req_id and passed item_id
$insertInquiryQuery = "INSERT INTO tbl_inquiry (inquiry_item_id, inquiry_request_id) VALUES (?, ?)";
$stmt3 = $conn->prepare($insertInquiryQuery);
$stmt3->bind_param("ii", $item_id, $item_req_id);

if ($stmt3->execute()) {
    echo "Inquiry successfully inserted into tbl_inquiry.<br>";
} else {
    die("Error inserting inquiry: " . $stmt3->error);
}

$stmt3->close();
$stmt2->close();
$stmt->close();
$conn->close();
?>
