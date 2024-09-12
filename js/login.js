
const adminBtn = document.getElementById('adminBtn');
const studentBtn = document.getElementById('studentBtn');
const loginButton = document.querySelector('.login-button');

const passwordField = document.getElementById('passwordField');
const showPasswordCheckbox = document.getElementById('showPassword');

let selectedRole = 'admin'; // Default to 'admin'

adminBtn.addEventListener('click', function() {
    adminBtn.classList.add('active');
    studentBtn.classList.remove('active');
    selectedRole = 'admin'; // Set selected role to 'admin'
});

studentBtn.addEventListener('click', function() {
    studentBtn.classList.add('active');
    adminBtn.classList.remove('active');
    selectedRole = 'student'; // Set selected role to 'student'
});

loginButton.addEventListener('click', function() {
    if (selectedRole === 'student') {
        window.location.href = 'Student_Viewing.html'; // Redirect to Student_Viewing.html
    } else if (selectedRole === 'admin') {
        window.location.href = 'admin_dashboard.html'; // Redirect to admin_dashboard.html
    }
});

showPasswordCheckbox.addEventListener('change', function() {
    if (this.checked) {
        // Show password
        passwordField.type = 'text';
    } else {
        // Hide password
        passwordField.type = 'password';
    }
});
