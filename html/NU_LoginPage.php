<?php 
    include("../php/connect.php");

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lost & Found Management System</title>
    <link rel="stylesheet" href="/css/nu_loginpage.css">
    
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="left-section">
        <div class="logo-container">
            <img src="assets/nu shield.png" alt="National University Logo"> 
            <div class="logo-text">NATIONAL<br>UNIVERSITY</div>
        </div>
        <h1><span>LOST & FOUND</span><br>MANAGEMENT<br>SYSTEM</h1>
    </div>
    <form class="right-section" action="../php/connect_NU_LoginPage.php" method="POST">
    <div class="login-box">
        <div class="role-buttons">
            <!-- Set the type of these buttons to "button" to avoid form submission -->
            <button type="button" class="active" id="adminBtn">ADMIN</button>
            <button type="button" id="studentBtn">STUDENT</button>
        </div>

        <div class="input-group">
            <i class="fas fa-envelope"></i> 
            <input type="text" id="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i> 
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <div class="show-password">
            <input type="checkbox" id="showPassword">
            <label for="showPassword">Show Password</label>
        </div>

        <button class="login-button" type="submit">LOG IN</button>
    </div>
</form>

<script type="text/javascript">
    window.history.pushState(null, "", window.location.href); 
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href); 
    };
</script>

<script>
    const adminBtn = document.getElementById('adminBtn');
    const studentBtn = document.getElementById('studentBtn');
    let selectedRole = 'admin'; // Default to 'admin'

    // Toggle the active class between admin and student buttons
    adminBtn.addEventListener('click', function() {
        adminBtn.classList.add('active');
        studentBtn.classList.remove('active');
        selectedRole = 'admin'; // Set selected role to 'admin'
    });

    studentBtn.addEventListener('click', function() {
    studentBtn.classList.add('active');
    adminBtn.classList.remove('active');
    selectedRole = 'student'; // Set selected role to 'student'
    // Redirect to StudentViewing.php
    window.location.href = '../html/Student_Viewing.php';
});


    // Optional: Send selectedRole value along with the form submission
    document.querySelector('.login-button').addEventListener('click', function(event) {
        // You can store the selected role in a hidden input if needed
        const roleInput = document.createElement('input');
        roleInput.type = 'hidden';
        roleInput.name = 'role';
        roleInput.value = selectedRole;
        document.querySelector('form').appendChild(roleInput);
    });

    // Show/Hide password functionality
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');
    showPasswordCheckbox.addEventListener('change', function() {
        passwordInput.type = this.checked ? 'text' : 'password';
    });
</script>



</body>
</html>
