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

// Initialize file path as null
$file_path = '../html/assets/noimage.jpg';

// Handle file upload if a file was uploaded
if (isset($_FILES['item_photo']) && $_FILES['item_photo']['error'] != UPLOAD_ERR_NO_FILE) {
    $file_name = $_FILES['item_photo']['name'];
    $temp_name = $_FILES['item_photo']['tmp_name'];
    $file_size = $_FILES['item_photo']['size'];
    $file_type = $_FILES['item_photo']['type'];
    $upload_directory = '../html/item-images/';
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
}

// Insert into tbl_full_name (first name, last name)
$insertFullNameQuery = "INSERT INTO tbl_full_name (fn_firstname, fn_lastname) VALUES (?, ?)";
$stmt = $conn->prepare($insertFullNameQuery);
$stmt->bind_param("ss", $firstName, $lastName);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {        
        $fn_id = $conn->insert_id;
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
    if ($stmt->affected_rows > 0) {    
        $time_date_id = $conn->insert_id;
    } else {
        echo "Error inserting date and time.<br>";
    }
} else {
    echo "Error in date and time query: " . $stmt->error . "<br>";
}

// Insert into tbl_item_description using the fn_id as the foreign key, with image path or null
$insertItemRequestQuery = "INSERT INTO tbl_item_description (item_full_name_id, item_founder_email, item_founder_stud_id, item_type_id, item_name_id, item_detailed_name, item_brand, item_location_id, item_specific_location_id, item_time_date_id, item_add_info, item_status_id, item_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt2 = $conn->prepare($insertItemRequestQuery);
$stmt2->bind_param("issiissiiisis", $fn_id, $item_founder_email, $item_founder_stud_id, $item_type_id, $item_name_id, $item_detailed_name, $item_brand, $item_location_id, $item_specific_location_id, $time_date_id, $item_add_info, $item_status_id, $file_path);

if ($stmt2->execute()) {
    echo "Item request successfully inserted into tbl_item_description.";
    header('Location: ../html/Admin_Dashboard.php');
} else {
    echo "Error inserting item request: " . $stmt2->error;
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
