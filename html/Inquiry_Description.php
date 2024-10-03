<?php
include("../php/connect2.php");

if (isset($_GET['inquiry_id'])) {
    $inquiryId = intval($_GET['inquiry_id']); // Sanitize the input for security

    try {
        // Step 2: Prepare the SQL query to fetch inquiry details based on the inquiry_id from tbl_inquiry and tbl_item_request
        $sql = "SELECT ir.*, 
                       fn.fn_firstname, fn.fn_lastname, 
                       it.it_name, 
                       iname.in_name,
                       loc.location_name, 
                       sloc.specific_location_name, 
                       tdate.date_lost, tdate.time_lost
                FROM tbl_inquiry i
                JOIN tbl_item_request ir ON i.inquiry_request_id = ir.item_req_id
                JOIN tbl_full_name fn ON ir.item_req_full_name_id = fn.fn_id
                JOIN tbl_item_type it ON ir.item_req_type_id = it.it_id
                JOIN tbl_item_name iname ON ir.item_req_name_id = iname.in_id
                JOIN tbl_location loc ON ir.item_req_location_id = loc.location_id
                JOIN tbl_specific_location sloc ON ir.item_req_specific_location_id = sloc.specific_location_id
                JOIN tbl_time_date tdate ON ir.item_req_time_date_id = tdate.time_date_id
                WHERE i.inquiry_id = :inquiry_id"; // Fetch based on inquiry_id

        // Step 3: Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':inquiry_id', $inquiryId, PDO::PARAM_INT);
        $stmt->execute();

        // Step 4: Fetch the result
        $inquiry = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($inquiry) {
            // If the inquiry is found, store the values
            $fullName = $inquiry['fn_firstname'] . ' ' . $inquiry['fn_lastname'];
            $itemType = $inquiry['it_name'];
            $itemName = $inquiry['in_name'];
            $itemBrand = $inquiry['item_req_brand'];
            $senderEmail = $inquiry['item_req_sender_email'];
            $senderId = $inquiry['item_req_sender_stud_id'];
            $itemReqDetailedName = $inquiry['item_req_detailed_name'];
            $itemInfo = $inquiry['item_req_add_info'];
            $itemPhoto = !empty($inquiry['item_req_photo']) ? '../assets/' . $inquiry['item_req_photo'] : 'https://via.placeholder.com/150'; // Placeholder if no image
            $itemLocation = $inquiry['location_name'];
            $itemSpecificLocation = $inquiry['specific_location_name'];
            $dateLost = $inquiry['date_lost'];
            $formattedDateLost = date("m/d/Y", strtotime($dateLost));
            $timeLost = $inquiry['time_lost'];
            $formattedTimeLost = date("h:i A", strtotime($timeLost));
            $selectedLocation = $inquiry['item_req_location_id'];
            $selectedSpecificLocation = $inquiry['item_req_specific_location_id'];
            $selectedType = $inquiry['item_req_type_id'];
            $selectedName = $inquiry['item_req_name_id'];
        } else {
            // If no inquiry is found, handle the error
            echo "Inquiry not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No inquiry selected.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lost & Found Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: #35408e;
            padding: 30px;
            /* Adjust padding if needed */
            border-radius: 15px;
            width: 80%;
            /* Adjust width to make it smaller or larger */
            max-width: 800px;
            /* Change this to desired max width */
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .custom-disabled {
            opacity: 1 !important; /* Override opacity */
            background-color: #fff; /* Optional: To ensure the background remains the same */
            color: #000; /* Optional: To keep the text color as it was */
            pointer-events: none; /* Ensures it's still non-interactive */
        }


        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #cfa92c;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        input[type="email"],
        input[type="time"],
        input[type="select"],
        input[type="option"],
        textarea {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }

        .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }

        button {
            padding: 10px;
            background-color: #cfa92c;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100px;
            font-size: 16px;
        }

        button:hover {
            background-color: #e0a800;
        }

        .submit-container {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 20px;
        }

        .popup {
            width: 400px;
            background: #fff;
            border-radius: 6px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            padding: 30px;
            color: #333;
            display: none;
            /* Hide popup by default */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .popup img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .close-popup-btn {
            background-color: #cfa92c;
            border: none;
            border-radius: 5px;
            color: b;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .close-popup-btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>
    <form class="form-container" enctype="multipart/form-data" action="../php/connect_Lost_and_Found_Student_Item.php" method="POST">
        <h2>LOST & FOUND FORM ITEM INQUIRY</h2>
        <form id="lostFoundForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Full Name</label>
                    <input type="text" id="fn_firstname" name="fn_firstname" value="<?php echo $fullName; ?>" readonly required>
                    <label for="firstName" style="margin-top: 8px" ;>Email</label>
                    <input type="email" id="item_req_sender_email" name="item_req_sender_email" placeholder="name@students.nu-dasma.edu.ph"  value="<?php echo $senderEmail; ?>" readonly required>
                    <label for="studentId" style="margin-top: 8px" ;>Student ID</label>
                    <input type="text" id="item_req_sender_stud_id" name="item_req_sender_stud_id" pattern="\d{4}-\d{6}" placeholder="0000-000000" value="<?php echo $senderId; ?>" readonly required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="it_name">Item Type</label>
                    <select id="it_name" name="item_req_type_id" disabled class="custom-disabled">
                        <option value="1" <?= ($selectedType == 1) ? 'selected' : ''; ?>>Personal Belongings</option>
                        <option value="2" <?= ($selectedType == 2) ? 'selected' : ''; ?>>School Supplies</option>
                        <option value="3" <?= ($selectedType == 3) ? 'selected' : ''; ?>>Electronic Devices</option>
                        <option value="4" <?= ($selectedType == 4) ? 'selected' : ''; ?>>Clothing</option>
                        <option value="5" <?= ($selectedType == 5) ? 'selected' : ''; ?>>Sports Equipment</option>
                        <option value="6" <?= ($selectedType == 6) ? 'selected' : ''; ?>>Documents and IDs</option>
                        <option value="7" <?= ($selectedType == 7) ? 'selected' : ''; ?>>Miscellaneous</option>
                    </select>
                    <input type="hidden" id="hidden_it_name" name="item_req_type_id" value="<?= $selectedType; ?>">
                    
                </div>
                <div class="form-group">
                    <label for="in_name">Item Name</label>
                    <select id="in_name" name="item_req_name_id" disabled class="custom-disabled">
                        <option value="1" <?= ($selectedName == 1) ? 'selected' : ''; ?>>Backpack</option>
                        <option value="2" <?= ($selectedName == 2) ? 'selected' : ''; ?>>Water Bottle</option>
                        <option value="3" <?= ($selectedName == 3) ? 'selected' : ''; ?>>Wallet</option>
                        <option value="4" <?= ($selectedName == 4) ? 'selected' : ''; ?>>Jewelry</option>
                        <option value="5" <?= ($selectedName == 5) ? 'selected' : ''; ?>>Eyeglasses</option>
                        <option value="6" <?= ($selectedName == 6) ? 'selected' : ''; ?>>Keys</option>
                        <option value="7" <?= ($selectedName == 7) ? 'selected' : ''; ?>>Notebook</option>
                        <option value="8" <?= ($selectedName == 8) ? 'selected' : ''; ?>>Textbook</option>
                        <option value="9" <?= ($selectedName == 9) ? 'selected' : ''; ?>>Pen</option>
                        <option value="10" <?= ($selectedName == 10) ? 'selected' : ''; ?>>Calculator</option>
                        <option value="11" <?= ($selectedName == 11) ? 'selected' : ''; ?>>Binder</option>
                        <option value="12" <?= ($selectedName == 12) ? 'selected' : ''; ?>>Laptop</option>
                        <option value="13" <?= ($selectedName == 13) ? 'selected' : ''; ?>>Tablet</option>
                        <option value="14" <?= ($selectedName == 14) ? 'selected' : ''; ?>>Phone</option>
                        <option value="15" <?= ($selectedName == 15) ? 'selected' : ''; ?>>Charger</option>
                        <option value="16" <?= ($selectedName == 16) ? 'selected' : ''; ?>>Headphones</option>
                        <option value="17" <?= ($selectedName == 17) ? 'selected' : ''; ?>>Jacket</option>
                        <option value="18" <?= ($selectedName == 18) ? 'selected' : ''; ?>>Scarf</option>
                        <option value="19" <?= ($selectedName == 19) ? 'selected' : ''; ?>>Shoes</option>
                        <option value="20" <?= ($selectedName == 20) ? 'selected' : ''; ?>>Gym Clothes</option>
                        <option value="21" <?= ($selectedName == 21) ? 'selected' : ''; ?>>Hat</option>
                        <option value="22" <?= ($selectedName == 22) ? 'selected' : ''; ?>>Soccer Ball</option>
                        <option value="23" <?= ($selectedName == 23) ? 'selected' : ''; ?>>Basketball</option>
                        <option value="24" <?= ($selectedName == 24) ? 'selected' : ''; ?>>Tennis Racket</option>
                        <option value="25" <?= ($selectedName == 25) ? 'selected' : ''; ?>>Gym Bag</option>
                    </select>
                    <input type="hidden" id="hidden_in_name" name="item_req_name_id" value="<?= $selectedName; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="detailedItemName">Detailed Item Name</label>
                    <input type="text" id="item_req_detailed_name" name="item_req_detailed_name" placeholder="ex. Sound Peats TRUEDOT" value="<?php echo $itemReqDetailedName; ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="brand">Item Brand (If Applicable)</label>
                    <input type="text" id="item_req_brand" name="item_req_brand" placeholder="Adidas" value="<?php echo $itemBrand; ?>" readonly required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="location_name">Item Lost Location</label>
                    <select id="location_name" name="item_req_location_id" disabled class="custom-disabled">
                        <option value="1" <?= ($selectedLocation == 1) ? 'selected' : ''; ?>>1st Floor</option>
                        <option value="2" <?= ($selectedLocation == 2) ? 'selected' : ''; ?>>4th Floor</option>
                        <option value="3" <?= ($selectedLocation == 3) ? 'selected' : ''; ?>>5th Floor</option>
                        <option value="4" <?= ($selectedLocation == 4) ? 'selected' : ''; ?>>Student Lounge</option>
                    </select>
                    <input type="hidden" id="hidden_location_name" name="item_req_location_id" value="<?= $selectedLocation; ?>">
                </div>

                <div class="form-group">
                    <label for="specific_location_name">Item Lost Specific Location</label>
                    <select id="specific_location_name" name="item_req_specific_location_id" disabled class="custom-disabled">
                        <option value="1" <?= ($selectedSpecificLocation == 1) ? 'selected' : ''; ?>>Entrance</option>
                        <option value="2" <?= ($selectedSpecificLocation == 2) ? 'selected' : ''; ?>>Comfort Room</option>
                        <option value="3" <?= ($selectedSpecificLocation == 3) ? 'selected' : ''; ?>>401 - 435</option>
                        <option value="4" <?= ($selectedSpecificLocation == 4) ? 'selected' : ''; ?>>Comfort Room Male</option>
                        <option value="5" <?= ($selectedSpecificLocation == 5) ? 'selected' : ''; ?>>Comfort Room Female</option>
                        <option value="6" <?= ($selectedSpecificLocation == 6) ? 'selected' : ''; ?>>Function Hall</option>
                        <option value="7" <?= ($selectedSpecificLocation == 7) ? 'selected' : ''; ?>>Accounting</option>
                        <option value="8" <?= ($selectedSpecificLocation == 8) ? 'selected' : ''; ?>>Registrar</option>
                        <option value="9" <?= ($selectedSpecificLocation == 9) ? 'selected' : ''; ?>>Hallway</option>
                        <option value="10" <?= ($selectedSpecificLocation == 10) ? 'selected' : ''; ?>>501 - 535</option>
                        <option value="11" <?= ($selectedSpecificLocation == 11) ? 'selected' : ''; ?>>Comfort Room Male</option>
                        <option value="12" <?= ($selectedSpecificLocation == 12) ? 'selected' : ''; ?>>Comfort Room Female</option>
                        <option value="13" <?= ($selectedSpecificLocation == 13) ? 'selected' : ''; ?>>Gym</option>
                        <option value="14" <?= ($selectedSpecificLocation == 14) ? 'selected' : ''; ?>>ITSO</option>
                        <option value="15" <?= ($selectedSpecificLocation == 15) ? 'selected' : ''; ?>>SDAO</option>
                        <option value="16" <?= ($selectedSpecificLocation == 16) ? 'selected' : ''; ?>>Hallway</option>
                        <option value="17" <?= ($selectedSpecificLocation == 17) ? 'selected' : ''; ?>>2nd Floor</option>
                        <option value="18" <?= ($selectedSpecificLocation == 18) ? 'selected' : ''; ?>>3rd Floor</option>
                        <option value="19" <?= ($selectedSpecificLocation == 19) ? 'selected' : ''; ?>>4th Floor</option>
                        <option value="20" <?= ($selectedSpecificLocation == 20) ? 'selected' : ''; ?>>5th Floor</option>
                    </select>
                    <input type="hidden" id="hidden_specific_location_name" name="item_req_specific_location_id" value="<?= $selectedSpecificLocation; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Item Lost Date</label>
                    <input type="date" id="date_lost" name="date_lost" value="<?php echo $dateLost; ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="time">Item Lost Time</label>
                    <input type="time" id="time_lost" name="time_lost" value="<?php echo $timeLost; ?>" readonly required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="info">Additional Information</label>
                    <textarea id="item_req_add_info" name="item_req_add_info" rows="4" readonly required><?php echo htmlspecialchars($itemInfo); ?></textarea>
                </div>
            </div>
            <div class="form-row">
            <div class="form-group">
    <label for="upload">Uploaded Image</label>
    <div style="text-align: center;">
    <img src="<?php echo $itemPhoto; ?>" alt="Item Photo" style="max-width: 50%; height: auto;">
    </div>
</div>
            </div>
            <div class="submit-container">
                <button type="submit">Release Item</button>
                <button type="button" class="close-popup-btn" onclick="closePopup()">Close</button>
            </div>
        </form>
    </form>

    <div class="popup" id="popup">
        <img src="assets/check.png" alt="Check">
        <h2>Thank You!</h2>
        <p>Your details have been successfully submitted.</p>
        <button class="close-popup-btn" onclick="closePopup()">Ok</button>
    </div>
    <script>
        // Show the popup after form submission
        document.getElementById('lostFoundForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            document.getElementById('popup').style.display = 'block'; // Show the popup
        });

        // Hide the popup and navigate back
        function closePopup() {
            document.getElementById('popup').style.display = 'none'; // Hide the popup
            window.history.back(); // Go back to the previous page
        }


        //drop down
        function updateItemName() {
            var itemType = document.getElementById("it_name").value;
            var itemName = document.getElementById("in_name");

            // Clear existing options
            itemName.innerHTML = '<option value="" disabled selected>Select Item Name</option>';

            var items = [];
            var itemIds = [];

            // Define item names and their corresponding IDs based on the selected item type
            if (itemType == "1") { // Personal Belongings
                items = ["Backpack", "Water Bottle", "Wallet", "Jewelry", "Eyeglasses", "Keys"];
                itemIds = [1, 2, 3, 4, 5, 6];
            } else if (itemType == "2") { // School Supplies
                items = ["Notebook", "Textbook", "Pen", "Calculator", "Binder"];
                itemIds = [7, 8, 9, 10, 11];
            } else if (itemType == "3") { // Electronic Devices
                items = ["Laptop", "Tablet", "Phone", "Charger", "Headphones"];
                itemIds = [12, 13, 14, 15, 16];
            } else if (itemType == "4") { // Clothing
                items = ["Jacket", "Scarf", "Shoes", "Gym Clothes", "Hat"];
                itemIds = [17, 18, 19, 20, 21];
            } else if (itemType == "5") { // Sports Equipment
                items = ["Soccer Ball", "Basketball", "Tennis Racket", "Gym Bag", "Sneakers"];
                itemIds = [22, 23, 24, 25, 26];
            } else if (itemType == "6") { // Documents and IDs
                items = ["Student ID", "Library Card", "Bus Pass", "Assignment Paper", "Permission Slip"];
                itemIds = [27, 28, 29, 30, 31];
            } else if (itemType == "7") { // Miscellaneous
                items = ["Musical Instrument", "Toy", "Umbrella", "Art Supplies"];
                itemIds = [32, 33, 34, 35];
            }

            // Populate the item name dropdown
            for (var i = 0; i < items.length; i++) {
                var option = document.createElement("option");
                option.value = itemIds[i];
                option.text = items[i];
                itemName.appendChild(option);
            }
        }

        function updateItemLostLocation() {
            var locationId = document.getElementById("location_name").value;
            var specificLocationSelect = document.getElementById("specific_location_name");

            specificLocationSelect.innerHTML = '<option value="" disabled selected>Select Item Lost Specific Location</option>';

            var specificLocations = [];
            var locationIds = [];

            if (locationId === "1") { // 1st Floor
                specificLocations = ["Entrance", "Comfort Room"];
                locationIds = [1, 2];
            } else if (locationId === "2") { // 4th Floor
                specificLocations = ["401 - 435", "Comfort Room Male", "Comfort Room Female", "Function Hall", "Accounting", "Registrar", "Hallway"];
                locationIds = [3, 4, 5, 6, 7, 8, 9];
            } else if (locationId === "3") { // 5th Floor
                specificLocations = ["501 - 535", "Comfort Room Male", "Comfort Room Female", "Gym", "ITSO", "SDAO", "Hallway"];
                locationIds = [10, 11, 12, 13, 14, 15, 16];
            } else if (locationId === "4") { // Student Lounge
                specificLocations = ["2nd Floor", "3rd Floor", "4th Floor", "5th Floor"];
                locationIds = [17, 18, 19, 20];
            }

            for (var i = 0; i < specificLocations.length; i++) {
                var option = document.createElement("option");
                option.value = locationIds[i];
                option.text = specificLocations[i];
                specificLocationSelect.appendChild(option);
            }
        }
    </script>
</body>

</html>