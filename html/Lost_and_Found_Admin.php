<?php
include("../php/connect.php");
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
    <form class="form-container" action="../php/connect_Lost_and_Found_Admin.php" method="POST">
        <h2>LOST & FOUND FORM</h2>
        <form id="lostFoundForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Name of Founder</label>
                    <input type="text" id="fn_firstname" name="fn_firstname" placeholder="First Name" required>
                    <input type="text" id="ln_lastname" name="ln_lastname" placeholder="Last Name" required>
                    <label for="firstName" style="margin-top: 8px" ;>Email</label>
                    <input type="email" id="item_founder_email" name="item_founder_email" placeholder="name@students.nu-dasma.edu.ph" required>
                    <label for="studentId" style="margin-top: 8px" ;>Student ID</label>
                    <input type="text" id="item_founder_stud_id" name="item_founder_stud_id" pattern="\d{4}-\d{6}" placeholder="0000-000000" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="it_name">Item Type</label>
                    <select id="it_name" name="item_type_id" onchange="updateItemName()" required>
                        <option value="" disabled selected>Select Item Type</option>
                        <option value="1">Personal Belongings</option>
                        <option value="2">School Supplies</option>
                        <option value="3">Electronic Devices</option>
                        <option value="4">Clothing</option>
                        <option value="5">Sports Equipment</option>
                        <option value="6">Documents and IDs</option>
                        <option value="7">Miscellaneous</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="in_name">Item Name</label>
                    <select id="in_name" name="item_name_id" required>
                        <option value="" disabled selected>Select Item Type First</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="detailedItemName">Detailed Item Name</label>
                    <input type="text" id="item_detailed_name" name="item_detailed_name" placeholder="ex. Sound Peats TRUEDOT" required>
                </div>
                <div class="form-group">
                    <label for="brand">Item Brand (If Applicable)</label>
                    <input type="text" id="item_brand" name="item_brand" placeholder="Adidas" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="location_name">Item Found Location</label>
                    <select id="location_name" name="item_location_id" onchange="updateItemLostLocation()" required>
                        <option value="" disabled selected>Select Item Lost Location</option>
                        <option value="1">1st Floor</option>
                        <option value="2">4th Floor</option>
                        <option value="3">5th Floor</option>
                        <option value="4">Student Lounge</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="specific_location_name">Item Found Specific Location</label>
                    <select id="specific_location_name" name="item_specific_location_id" required>
                        <option value="" disabled selected>Select Item Lost Location First</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Item Found Date</label>
                    <input type="date" id="date_lost" name="date_lost" placeholder="ex. Sound Peats TRUEDOT" required>
                </div>
                <div class="form-group">
                    <label for="time">Item Found Time</label>
                    <input type="time" id="time_lost" name="time_lost" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="info">Please provide detailed information about the item found</label>
                    <textarea id="item_add_info" name="item_add_info" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="upload">Upload an image of found item (Optional)</label>
                    <input type="file" id="item_photo" name="item_photo" optional>
                </div>
            </div>
            <div class="submit-container">
                <button type="submit">Submit</button>
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