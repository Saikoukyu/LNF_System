<?php
include("connect.php"); 

// Retrieve form values
$fn_firstname = $_POST['fn_firstname'];
$fn_lastname = $_POST['ln_lastname'];
$item_req_sender_email = $_POST['item_req_sender_emai'];
$item_req_sender_stud_id = $_POST['item_req_sender_stud_id'];
$item_req_type_id = $_POST['item_req_type_id'];
$item_req_name_id = $_POST['item_req_name_id'];
$item_req_detailed_name = $_POST['item_req_detailed_name'];
$item_req_brand = $_POST['item_req_brand'];
$item_req_location_id = $_POST['item_req_location_id'];
$item_req_specific_location_id = $_POST['item_req_specific_location_id'];
$item_req_time_date_id = $_POST['item_req_time_date_id'];
$item_req_add_info = $_POST['item_req_add_info'];
$item_req_photo = $_FILES['item_req_photo']['tmp_name']; // Handle file upload if needed

// Insert into tbl_full_name
$insertFullNameQuery = "INSERT INTO tbl_full_name (fn_firstname, fn_lastname) VALUES (?, ?)";
$stmt = $conn->prepare($insertFullNameQuery);
$stmt->bind_param("ss", $fn_firstname, $fn_lastname);

if ($stmt->execute()) {
    // Get the last inserted ID
    $fn_id = $conn->insert_id;
    
    // Insert into tbl_item_request
    $insertItemRequestQuery = "INSERT INTO tbl_item_request (item_req_full_name_id, item_req_sender_email, item_req_sender_stud_id, item_req_type_id, item_req_name_id, item_req_detailed_name, item_req_brand, item_req_location_id, item_req_specific_location_id, item_req_time_date_id, item_req_add_info, item_req_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertItemRequestQuery);
    $stmt->bind_param("issiiiiiiiis", $fn_id, $item_req_sender_email, $item_req_sender_stud_id, $item_req_type_id, $item_req_name_id, $item_req_detailed_name, $item_req_brand, $item_req_location_id, $item_req_specific_location_id, $item_req_time_date_id, $item_req_add_info);
    
    if ($stmt->execute()) {
        echo "Item request successfully submitted.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
