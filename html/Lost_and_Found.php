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
			padding: 30px; /* Adjust padding if needed */
			border-radius: 15px;
			width: 80%; /* Adjust width to make it smaller or larger */
			max-width: 800px; /* Change this to desired max width */
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
            gap:50px;
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
            display: none; /* Hide popup by default */
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
    <div class="form-container">
        <h2>LOST & FOUND FORM</h2>
        <form id="lostFoundForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Name</label>
                    <input type="text" id="firstName" placeholder="First Name" required>
                    <input type="text" id="lastName" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="itemType">Item Type</label>
                    <input type="text" id="itemType" required>
                </div>
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" id="brand" required>
                </div>
            </div>
            <div class="form-row">
                <label>Purpose of Contact</label>
                <div class="radio-group">
                    <input type="radio" id="lost" name="purpose" value="lost" required>
                    <label for="lost">Lost Item</label>
                    <input type="radio" id="found" name="purpose" value="found" required>
                    <label for="found">Found Item</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Where did you lose/find the item?</label>
                    <input type="text" id="location" required>
                </div>
                <div class="form-group">
                    <label for="date">When did you lose/find the item?</label>
                    <input type="date" id="date" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="details">Please provide detailed information about the item lost/found</label>
                    <textarea id="details" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="upload">Upload an image of lost/found item (Optional)</label>
                    <input type="file" id="upload" optional>
                </div>
            </div>
            <div class="submit-container">
                <button type="submit">Submit</button>
                <button type="button" class="close-popup-btn" onclick="closePopup()">Close</button>
            </div>
        </form>
    </div>

    <div class="popup" id="popup">
        <img src="assets/check.png" alt="Check">
        <h2>Thank You!</h2>
        <p>Your details have been successfully submitted.</p>
        <button class="close-popup-btn" onclick="closePopup()">Ok</button>
    </div>
    <script>
        // Show the popup after form submission
        document.getElementById('lostFoundForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Prevent the default form submission
            document.getElementById('popup').style.display = 'block';  // Show the popup
        });

        // Hide the popup and navigate back
        function closePopup() {
            document.getElementById('popup').style.display = 'none';  // Hide the popup
            window.history.back(); // Go back to the previous page
        }
    </script>
</body>
</html>
