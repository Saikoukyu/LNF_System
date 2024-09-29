<?php
include('connect.php');

// Define the maximum file size in bytes (15 MB)
$maxFileSize = 15 * 1024 * 1024; // 15 MB

// Allowed image types
$allowedFileTypes = ['image/jpeg', 'image/jpg', 'image/png'];

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
$item_status_id = '1'; // Assuming status is a constant for now

// Handle file upload
$file_name = $_FILES['item_photo']['name']; // Use the name of your input file field
$temp_name = $_FILES['item_photo']['tmp_name'];
$file_size = $_FILES['item_photo']['size'];
$file_type = $_FILES['item_photo']['type'];
$upload_directory = '../html/item-images/'; // Absolute path to the upload directory
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
if (!move_uploaded_file($temp_name, $file_path)) {
    die("Failed to upload image.<br>");
}

// Insert into tbl_full_name (first name, last name)
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

// Insert into tbl_time_date (date and time lost)
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

// Insert into tbl_item_description using the fn_id as the foreign key
$insertItemRequestQuery = "INSERT INTO tbl_item_description (item_full_name_id, item_founder_email, item_founder_stud_id, item_type_id, item_name_id, item_detailed_name, item_brand, item_location_id, item_specific_location_id, item_time_date_id, item_add_info, item_status_id, item_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt2 = $conn->prepare($insertItemRequestQuery);
$stmt2->bind_param("issiissiiisis", $fn_id, $item_founder_email, $item_founder_stud_id, $item_type_id, $item_name_id, $item_detailed_name, $item_brand, $item_location_id, $item_specific_location_id, $time_date_id, $item_add_info, $item_status_id, $file_path);

if ($stmt2->execute()) {
    echo "Item request successfully inserted into tbl_item_description.";
} else {
    echo "Error inserting item request: " . $stmt2->error;
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
