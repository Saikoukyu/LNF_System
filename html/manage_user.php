<?php
include("../php/connect.php");
$sql = "SELECT * FROM tbl_do_admin";
$result = $conn->query($sql);

session_start(); // Start the session
$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage User</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
            Hello, <?php
            // Dynamically show the username or placeholder based on session (assumed username is stored in session)
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
            ?>
        </div>
        <ul>
            <li onclick="window.location.href='Admin_Dashboard.php'">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </li>
            <li onclick="window.location.href='item view.php'">
                <i class="fas fa-eye"></i><span>Item View</span>
            </li>
            <li onclick="window.location.href='Admin_Report.php'">
                <i class="fas fa-file-alt"></i><span>Report</span>
            </li>
            <li onclick="window.location.href='Admin_Admin.php'">
                <i class="fas fa-user"></i><span>Admin</span>
            </li>
            <?php if ($role == 'IT_Admin') : ?>
            <li onclick="window.location.href='Admin_ITAdmin.php'">
                <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
            </li>
            <?php endif; ?>
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
        <a href="Lost_and_Found.php" class="add-lost-found">
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
        .addEventListener("click", function(event) {
          event.preventDefault(); // Prevent the default action
          this.closest(".dropdown").classList.toggle("open"); // Toggle the dropdown menu
        });

      document.addEventListener("click", function(event) {
        var isClickInside = document
          .querySelector(".dropdown")
          .contains(event.target);

        if (!isClickInside) {
          document.querySelector(".dropdown").classList.remove("open");
        }
      });

      const logoutButton = document.getElementById("logoutButton");

      logoutButton.addEventListener("click", function() {
        window.location.href = "NU_LoginPage.php";
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
              <th>Password</th>
              <th>Manage</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Check if there are any records
            if ($result->num_rows > 0) {
              // Output each row of data from the database
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td> Name </td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
               // Check the role and conditionally show buttons
            if ($row['role'] === 'IT_Admin') {
              // Do not show edit or delete buttons
              echo "<td></td>";
              echo "<td></td>";
          } else if ($row['role'] === 'Admin') {
              // Show edit and delete buttons
              echo "<td><button class='edit-btn' onclick='openEditModal(this)'>Edit</button></td>";
              echo "<td><button class='delete-btn btn btn-danger' onclick='openDeleteModal(\"" . $row['username'] . "\", \"" . $row['username'] . "\")'>Delete</button></td>";
          }
      
          echo "</tr>";
      }
            } else {
              echo "<tr><td colspan='4'>No users found in the database.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Modal for Editing User -->
      <div class="modal-overlay" id="editModal">
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

            <div class="button-container">
              <button type="button" onclick="saveEdit()">Save Changes</button>
              <button type="button" onclick="closeEditModal()">Cancel</button>
            </div>

          </form>
        </div>
      </div>

      <script>
        let selectedEditRow; // To track the selected row for editing
        let originalUsername; // To track the original username before editing

        // Function to open the Edit Modal and populate the fields with current user data
        function openEditModal(button) {
          const row = button.closest('tr'); // Get the row of the clicked Edit button
          selectedEditRow = row; // Store the selected row for updating later
          originalUsername = row.cells[1].textContent; // Save the original username

          // Populate the modal with current user data
          document.getElementById('editUsername').value = row.cells[1].textContent; // Username
          document.getElementById('editPassword').value = row.cells[2].textContent; // Password

          // Display the modal
          document.getElementById("editModal").style.display = "flex";
        }

        // Function to close the Edit Modal
        function closeEditModal() {
          document.getElementById("editModal").style.display = "none";
        }

        // Function to handle saving edited user details
        function saveEdit() {
          const newUsername = document.getElementById('editUsername').value;
          const newPassword = document.getElementById('editPassword').value;

          // Proceed with updating user details via AJAX
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "../php/edit_user.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onload = function() {
            if (xhr.status === 200) {
              alert(xhr.responseText); // Show success or error message
              window.location.reload(); // Reload the page to update the table
            } else {
              alert("Error updating user. Please try again.");
            }
          };

          xhr.send("originalUsername=" + encodeURIComponent(originalUsername) +
            "&newUsername=" + encodeURIComponent(newUsername) +
            "&newPassword=" + encodeURIComponent(newPassword));

          // Close the modal after saving
          closeEditModal();
        }
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
        document.addEventListener('DOMContentLoaded', function() {
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
          document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
              const row = this.closest('tr'); // Get the table row
              const name = row.querySelector('td:nth-child(1)').textContent; // Get the user name from the first column
              openDeleteModal(name, row); // Open the modal
            });
          });

          // When the Yes button is clicked, delete the user (remove the row)
          confirmDelete.addEventListener('click', function() {
            if (selectedRow) {
              selectedRow.remove(); // Remove the row from the table
            }
            deleteModal.style.display = 'none'; // Close the modal
          });

          // Close modal when clicking "No" or the "X" icon
          cancelDelete.addEventListener('click', function() {
            deleteModal.style.display = 'none';
          });

          closeModal.addEventListener('click', function() {
            deleteModal.style.display = 'none';
          });

          // Close modal if the user clicks outside the modal
          window.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
              deleteModal.style.display = 'none';
            }
          });
        });
      </script>

      <!-- Right Section: Add New User Form -->
      <div class="right-section">
        <h2>Add New User</h2>
        <form id="userForm" action="../php/connect_manage_user.php" method="POST">
          <label for="name">Name</label>
          <input
            type="text"
            id="name"
            name="name"
            placeholder="Enter full name"
            required />

          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Add user email"
            required />

          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            required />

          <label for="confirmpassword">Confirm Password</label>
          <input
            type="password"
            id="confirmpassword"
            name="confirmpassword"
            placeholder="Confirm password"
            required />

          <button id="adduserbtn" type="submit">Add User</button>
        </form>
      </div>
    </div>


</body>

</html>

<script>
  let selectedUsername; // To store the username for deletion

  // Function to open the delete confirmation modal
  function openDeleteModal(name, username) {
    document.getElementById('name').textContent = `Are you sure you want to delete user ${username}?`;
    deleteModal.style.display = 'block';
    selectedUsername = username; // Store the username for later deletion
  }

  // Function to handle deletion when the "Yes" button is clicked
  document.getElementById('confirmDelete').addEventListener('click', function() {
    // Proceed with deletion
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/delete_user.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      if (xhr.status === 200) {
        alert(xhr.responseText); // Show success or error message
        window.location.reload(); // Reload the page to update the table
      } else {
        alert("Error deleting user. Please try again.");
      }
    };
    xhr.send("username=" + encodeURIComponent(selectedUsername));

    // Close the modal
    deleteModal.style.display = 'none';
  });

  // Function to cancel and close the delete modal
  document.getElementById('cancelDelete').addEventListener('click', function() {
    deleteModal.style.display = 'none'; // Close the modal without deleting
  });

  // Close the modal when clicking the "X" button
  document.querySelector('.modal .close').addEventListener('click', function() {
    deleteModal.style.display = 'none';
  });

  // Close the modal if the user clicks outside of it
  window.addEventListener('click', function(event) {
    if (event.target === deleteModal) {
      deleteModal.style.display = 'none';
    }
  });
</script>

<?php
// Close the database connection
$conn->close();
?>