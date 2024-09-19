<?php 
    include("../php/connect.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage User</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link rel="stylesheet" href="/css/admin_manageuser.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>

  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="menu-toggle">
        <i class="fas fa-bars"></i>
        <span>MENU</span>
      </div>
      <div class="sidebar-greeting">
        Hello, Admin 1
        <!-- Placeholder for admin name -->
      </div>
      <ul>
        <li onclick="window.location.href='Admin_Dashboard.html'">
          <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </li>
        <li onclick="window.location.href='item view.html'">
          <i class="fas fa-eye"></i><span>Item View</span>
        </li>
        <li onclick="window.location.href='Admin_Report.html'">
          <i class="fas fa-file-alt"></i><span>Report</span>
        </li>
        <li onclick="window.location.href='Admin_Admin.html'">
          <i class="fas fa-user"></i><span>Admin</span>
        </li>
        <li onclick="window.location.href='Admin_ITAdmin.html'">
          <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
        </li>
      </ul>
    </div>

    <!-- Content  -->
    <div class="content">
      <!-- Header -->
      <div class="header">
        <span class="system-title">
          <span>LOST & FOUND</span>
          <span>Management System</span>
        </span>

        <div class="right-menu">
          <a href="#" class="add-lost-found">
            <span class="plus">+</span>
            <span class="lost">Lost</span>
            <span class="and">&</span>
            <span class="found">Found</span>
          </a>
          <div class="dropdown">
            <a href="#" class="dropdown-toggle">
              <i class="fas fa-user"></i> Admin 1
              <!-- Placeholder for admin name -->
              <i class="fas fa-caret-down dropdown-caret"></i>
            </a>
            <div class="dropdown-content">
              <a href="#" id="logoutButton">Logout</a>
            </div>
          </div>
        </div>
      </div>

      <script>
        // Dropdown toggle script
        document
          .querySelector(".dropdown-caret")
          .addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the default action
            this.closest(".dropdown").classList.toggle("open"); // Toggle the dropdown menu
          });

        document.addEventListener("click", function (event) {
          var isClickInside = document
            .querySelector(".dropdown")
            .contains(event.target);

          if (!isClickInside) {
            document.querySelector(".dropdown").classList.remove("open");
          }
        });

        const logoutButton = document.getElementById("logoutButton");

        logoutButton.addEventListener("click", function () {
          window.location.href = "NU_LoginPage.html";
        });
      </script>

      <!-- Admin Dashboard Title with Icon -->
      <div class="admin-dashboard">
        <i class="fas fa-tachometer-alt"></i>
        <span>Manage User</span>
        <!-- Placeholder for dashboard title -->
      </div>
      <hr class="header-line" />

      <!-- table and form content - DEMO --> 
      <div class="content-section">
        <!-- Left Section: User Management Table -->
        <div class="left-section">
          <table id="userTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Password</th>
                <th>Manage</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
              <tr></tr>
                <td>Lost Found</td>
                <td>lostandfound@gmail.com</td>
                <td>Admin</td>
                <td>main@dmin123</td>
                <td><button class="edit-btn" onclick="openModal(this)">Edit</button></td>
                <td><button class="delete-btn btn btn-danger">Delete</button></td>
              </tr>
              <tr>
                <td>Jane Smith</td>
                <td>jane.smith@gmail.com</td>
                <td>Admin</td>
                <td>co@dmin123</td>
                <td><button class="edit-btn" onclick="openModal(this)">Edit</button></td>
                <td><button class="delete-btn btn btn-danger">Delete</button></td>
              </tr>
              <tr>
                <td>Jordan Clark</td>
                <td>clarkjm@students.nu-dasma.edu.ph</td>
                <td>Student</td>
                <td>123456</td>
                <td><button class="edit-btn" onclick="openModal(this)">Edit</button></td>
                <td><button class="delete-btn btn btn-danger">Delete</button></td>
              </tr>
              <!-- Add more user rows as needed - this is just a demo-->
            </tbody>
          </table>
        </div>

    <!-- Modal for Editing User -->
    <div class="modal-overlay" id="modal-overlay">
      <div class="modal">
          <span class="close-btn" onclick="closeModal()">&times;</span>
          <h2>Edit User</h2>
          <form>
              <input type="text" id="edit-name" name="name" placeholder="Edit user full name" required />
              <input type="email" id="edit-email" name="email" placeholder="Edit user school or work email" required />
              
              <div class="password-container">
                  <input type="password" id="edit-password" name="password" placeholder="Edit user password" required />
                  <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('edit-password')">
                      <i class="fas fa-eye"></i>
                  </button>
              </div>
              
              <div class="password-container">
                  <input type="password" id="edit-confirm-password" name="confirm-password" placeholder="Confirm user password" required />
                  <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('edit-confirm-password')">
                      <i class="fas fa-eye"></i>
                  </button>
              </div>

              <select id="edit-user-type" name="type" title="User Type">
                  <option value="Student">Student</option>
                  <option value="Admin">Admin</option>
              </select>
              
              <button type="button">Save Changes</button>
          </form>
      </div>
  </div>
  
      <script>
         let selectedRow; // Track the selected row for editing
// Function to open the modal and populate the fields with current user data
function openModal(button) {
    const row = button.closest('tr'); // Get the row of the clicked edit button
    selectedRow = row; // Save the selected row for updating later

    // Populate the modal fields with current data
    document.getElementById('edit-name').value = row.cells[0].textContent;
    document.getElementById('edit-email').value = row.cells[1].textContent;
    document.getElementById('edit-password').value = row.cells[3].textContent;
    document.getElementById('edit-confirm-password').value = row.cells[3].textContent;
    document.getElementById('edit-user-type').value = row.cells[2].textContent;

    // Display the modal
    document.getElementById("modal-overlay").style.display = "flex";
}

// Function to close the modal
function closeModal() {
    document.getElementById("modal-overlay").style.display = "none";
}

// Function to toggle password visibility
function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

// Function to handle saving changes
function saveChanges() {
    const name = document.getElementById('edit-name').value;
    const email = document.getElementById('edit-email').value;
    const password = document.getElementById('edit-password').value;
    const confirmPassword = document.getElementById('edit-confirm-password').value;
    const userType = document.getElementById('edit-user-type').value;

    // Validate if passwords match
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    // Update the selected row with the new values
    selectedRow.cells[0].textContent = name;
    selectedRow.cells[1].textContent = email;
    selectedRow.cells[2].textContent = userType;
    selectedRow.cells[3].textContent = password;

    // Close the modal after saving
    closeModal();
}

// Attach event listener to the Save Changes button
document.querySelector('.modal button[type="button"]').addEventListener('click', saveChanges);

// Close modal if the user clicks outside the modal
window.addEventListener('click', function (event) {
    const modalOverlay = document.getElementById("modal-overlay");
    if (event.target === modalOverlay) {
        closeModal();
    }
});
      </script>
      

      <!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Delete User</h2>
      <p id="name"></p> <!-- This will display the user name dynamically -->
      <div class="button-container">
            <button class="btn-danger" id="confirmDelete">Yes</button>
            <button class="btn-secondary" id="cancelDelete">No</button>
        </div>
    </div>
  </div>

  <!--THIS IS DEMO IN DELETE- Hindi mo ma dedelete ung nagawa mong bagong user dahil fix ung code na dinedelete neto. unless na may database --> 
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    const closeModal = document.querySelector('.close');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');
    let selectedRow;

    // Function to open the delete confirmation modal
    function openDeleteModal(name, row) {
        document.getElementById('name').textContent = `Are you sure you want to delete user ${name}?`;
        deleteModal.style.display = 'block';
        selectedRow = row;
    }

    // Attach event listeners to all delete buttons
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');  // Get the table row
            const name = row.querySelector('td:nth-child(1)').textContent;  // Get the user name from the first column
            openDeleteModal(name, row);  // Open the modal
        });
    });

    // When the Yes button is clicked, delete the user (remove the row)
    confirmDelete.addEventListener('click', function () {
        if (selectedRow) {
            selectedRow.remove();  // Remove the row from the table
        }
        deleteModal.style.display = 'none';  // Close the modal
    });

    // Close modal when clicking "No" or the "X" icon
    cancelDelete.addEventListener('click', function () {
        deleteModal.style.display = 'none';
    });

    closeModal.addEventListener('click', function () {
        deleteModal.style.display = 'none';
    });

    // Close modal if the user clicks outside the modal
    window.addEventListener('click', function (event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });
});
    </script>

        <!-- Right Section: Add New User Form -->
        <div class="right-section">
          <h2>Add New User</h2>
          <form id="userForm" action="#" method="POST">
            <label for="name">Name</label>
            <input
              type="text"
              id="username"
              name="name"
              placeholder="Enter full name"
              required
            />

            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Add user email"
              required
            />

            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter password"
              required
            />

            <label for="confirmpassword">Confirm Password</label>
            <input
              type="password"
              id="confirmpassword"
              name="confirmpassword"
              placeholder="Confirm password"
              required
            />

            <label for="type">User Type</label>
            <select id="type" name="type" required>
              <option value="Student">Student</option>
              <option value="Admin">Admin</option>
            </select>

            <button id="adduserbtn" type="submit">Add User</button>
          </form>
        </div>
      </div>
      
      <script>
        // Handle form submission and add new user - THIS IS JUST A DEMO
        document.getElementById('userForm').addEventListener('submit', function (e) {
          e.preventDefault(); // Prevent default form submission

          // Get form values
          const username = document.getElementById('username').value;
          const email = document.getElementById('email').value;
          const password = document.getElementById('password').value;
          const confirmPassword = document.getElementById('confirmpassword').value;
          const userType = document.getElementById('type').value;

          // Validate if passwords match
          if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
          }

          // Create a new row in the table
          const table = document.getElementById('userTable').getElementsByTagName('tbody')[0];
          const newRow = table.insertRow();

          // Insert new cells in the row
          const usernameCell = newRow.insertCell(0);
          const emailCell = newRow.insertCell(1);
          const typeCell = newRow.insertCell(2);
          const passwordCell = newRow.insertCell(3);
          const manageCell = newRow.insertCell(4);
          const removeCell = newRow.insertCell(5);

          // Assign values to the cells
          usernameCell.textContent = username;
          emailCell.textContent = email;
          typeCell.textContent = userType;
          passwordCell.textContent = password;

          // Add buttons for edit and delete
          manageCell.innerHTML = `<button class="edit-btn" onclick="openModal()">Edit</button>`;
          removeCell.innerHTML = `<button class="delete-btn btn btn-danger">Delete</button>`;

          // Clear form fields after submission
          document.getElementById('userForm').reset();
        });

        // Additional scripts for handling edit, delete functionality will be added here...
      </script>

  </body>
</html>
