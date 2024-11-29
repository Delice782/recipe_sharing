<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_recipe.php');
    exit();
}

include 'db_connect.php'; // Include the database connection

// Fetch users from the database
function fetchUsers($page, $usersPerPage) {
    global $conn;
    $offset = ($page - 1) * $usersPerPage;
    $sql = "SELECT user_id, fname, lname, email, role, created_at FROM users LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $usersPerPage);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch total users count for pagination
function fetchTotalUsers() {
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    error_log("Number of users fetched: " . $result->num_rows);
    return $row['total'];
}

// Map roles to their respective names
function getRoleName($role) {
    if ($role == 1) {
        return 'Superadmin';
    } elseif ($role == 2) {
        return 'Regular Admin';
    } else {
        return 'User';
    }
}

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$usersPerPage = 10;
$totalUsers = fetchTotalUsers();
$users = fetchUsers($currentPage, $usersPerPage);

// Calculate pagination
$totalPages = ceil($totalUsers / $usersPerPage);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="fetch_users.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .user-management-container {
            margin: 20px;
        }

        .main-content {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination button {
            margin: 0 0.5rem;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        /* Modal is hidden by default */
        #userModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background */
        }


        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Add padding to table cells */
        #userTable td, #userTable th {
            padding: 50px 30px; 
        }

        /* Ensure button is enabled and visible */
        #addUserBtn {
            display: block;
            visibility: visible;
            pointer-events: all; 
        }

        
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group small {
            color: #666;
            font-size: 0.85em;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="user-management-container">
        <main class="main-content">
            <h1>User Management</h1>
            <section class="card">
                <h2>All Users</h2>
                <button id="addUserBtn">Add New User</button>
                <div class="user-list">
                    <table id="userTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php
                                foreach ($users as $user) {
                                    // Convert role integer to role name
                                    $roleName = $user['role'] == 1 ? 'Superadmin' : 'Regular Admin';
                                    echo "<tr data-id='{$user['user_id']}'>
                                            <td>{$user['user_id']}</td>
                                            <td>{$user['fname']} {$user['lname']}</td>
                                            <td>{$user['email']}</td>
                                            <td>{$roleName}</td>
                                            <td>" . date("Y-m-d H:i:s", strtotime($user['created_at'])) . "</td>
                                            <td>
                                                <button onclick='viewUser({$user['user_id']})'>View</button>
                                                <button onclick='editUser({$user['user_id']})'>Edit</button>
                                                <button onclick='deleteUser({$user['user_id']})'>Delete</button>
                                                <button onclick='approveUser({$user['user_id']})'>Approve</button>
                                            </td>
                                          </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <button id="prevPage" disabled>Prev</button>
                        <span id="pageInfo"></span>
                        <button id="nextPage">Next</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="userModalTitle">Add User</h2>
            <form id="userForm">
                <div class="form-group">
                    <label for="userName">Name:</label>
                    <input type="text" id="userName" required>
                </div>
                
                <div class="form-group">
                    <label for="userEmail">Email:</label>
                    <input type="email" id="userEmail" required>
                </div>
                
                <div class="form-group">
                    <label for="userPassword">Password:</label>
                    <input type="password" id="userPassword" required minlength="6">
                    <small>Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="userRole">Role:</label>
                    <select id="userRole" required>
                        <option value="">Select Role</option>
                        <option value="1">Superadmin</option>
                        <option value="2">Regular Admin</option>
                        <option value="3">User</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Save</button>
            </form>
        </div>
    </div>


    

    <!-- View User Modal -->
    <div id="viewUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>View User</h2>
            <p><strong>Name:</strong> <span id="viewUserName"></span></p>
            <p><strong>Email:</strong> <span id="viewUserEmail"></span></p>
            <p><strong>Role:</strong> <span id="viewUserRole"></span></p>
            <p><strong>Created At:</strong> <span id="viewUserCreatedAt"></span></p>
        </div>
    </div>

    <script>
        const userModal = document.getElementById('userModal');
        const viewUserModal = document.getElementById('viewUserModal');
        const userTableBody = document.getElementById('userTableBody');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfoEl = document.getElementById('pageInfo');
        const userForm = document.getElementById('userForm');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeButtons = document.querySelectorAll('.close');

        // Add User button click handler
        addUserBtn.onclick = function() {
            document.getElementById('userModalTitle').innerText = 'Add New User';
            userForm.reset();
            currentUserId = null;
            userModal.style.display = 'block';
        };

        // Close button handlers
        closeButtons.forEach(button => {
            button.onclick = function() {
                userModal.style.display = 'none';
                viewUserModal.style.display = 'none';
            };
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target === userModal || event.target === viewUserModal) {
                userModal.style.display = 'none';
                viewUserModal.style.display = 'none';
            }
        };

        // Form submission handler
        userForm.onsubmit = function(event) {
            event.preventDefault();

            const name = document.getElementById('userName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const role = document.getElementById('userRole').value.trim();

            if (!name || !email || !role) {
                alert('Please fill in all fields.');
                return;
            }

            const formData = new FormData();
            formData.append('action', currentUserId ? 'edit' : 'add');
            if (currentUserId) {
                formData.append('user_id', currentUserId);
            }
            formData.append('name', name);
            formData.append('email', email);
            formData.append('role', role);

            fetch(currentUserId ? 'edit_user.php' : 'add_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('User added successfully') || data.includes('User updated successfully')) {
                    alert('User saved successfully!');
                    fetchAndDisplayUsers(currentPage);
                    userModal.style.display = 'none';
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => {
                console.error('Error saving user:', error);
                alert('Error saving user. Please try again.');
            });
        };

        let currentuser_id = null;
        let currentPage = 1;
        const usersPerPage = 10;
        let users = <?php echo json_encode($users); ?>;

        prevPageBtn.onclick = function () {
            console.log('Prev button clicked');
            console.log(`Current Page: ${currentPage}, Total Pages: ${totalPages}`);
            if (currentPage > 1) {
                fetchAndDisplayUsers(currentPage - 1);
            }
        };

        nextPageBtn.onclick = function () {
            console.log('Next button clicked');
            console.log(`Current Page: ${currentPage}, Total Pages: ${totalPages}`);
            if (currentPage < totalPages) {
                fetchAndDisplayUsers(currentPage + 1);
            }
        };

        console.log(`Current Page: ${currentPage}, Total Pages: ${totalPages}`);

        // Open Add User Modal
        document.getElementById('addUserBtn').onclick = function() {
            console.log("Add User button clicked")
            document.getElementById('userModalTitle').innerText = 'Add New User';
            userForm.reset();
            currentUserId = null;
            userModal.style.display = 'block';
            console.log("User Modal should now be visible");
        };

        document.querySelector('.close').onclick = function() {
            userModal.style.display = 'none';
        };


        document.getElementById('userForm').onsubmit = function(event) {
            event.preventDefault();

            const name = document.getElementById('userName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const role = document.getElementById('userRole').value.trim();

            if (name === '' || email === '' || role === '') {
                alert('Please fill in all fields.');
                return;
            }

            const formData = new FormData();
            formData.append('action', currentUserId ? 'edit' : 'add');
            formData.append('user_id', currentUserId);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('role', role);

            fetch(currentUserId ? 'edit_user.php' : 'add_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('User added successfully') || data.includes('User updated successfully')) {
                    alert('User saved successfully!');
                    fetchAndDisplayUsers(currentPage);  // Refresh user list
                    userModal.style.display = 'none';  // Close the modal
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => {
                console.error('Error saving user:', error);
            });
        };

        // Wait for the document to load completely
        document.addEventListener('DOMContentLoaded', function() {
            // Select the button element
            const addUserBtn = document.getElementById('addUserBtn');
            
            // Ensure the button exists and add an event listener for the click event
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function() {
                    console.log("Button clicked!"); 
                });
            } else {
                console.error("Button not found!");
            }
        });
        console.log('Initial users data:', users);
        console.log('View modal element:', viewUserModal);
        
        function testModal() {
            viewUserModal.style.display = 'block';
        }

        function viewUser(user_id) {
            console.log('View button clicked for user ID:', user_id); 
            
            // Get the user data from the table row instead of relying on the users array
            const userRow = document.querySelector(`tr[data-id="${user_id}"]`);
            
            if (userRow) {
                // Get data from table cells
                const cells = userRow.getElementsByTagName('td');
                const userName = cells[1].textContent;  // Name is in second column
                const userEmail = cells[2].textContent; // Email is in third column
                const userRole = cells[3].textContent;  // Role is in fourth column
                const createdAt = cells[4].textContent; // Created At is in fifth column

                // Update modal with user data
                document.getElementById('viewUserName').textContent = userName;
                document.getElementById('viewUserEmail').textContent = userEmail;
                document.getElementById('viewUserRole').textContent = userRole;
                document.getElementById('viewUserCreatedAt').textContent = createdAt;

                // Show the modal
                const viewUserModal = document.getElementById('viewUserModal');
                viewUserModal.style.display = 'block';
                
                // Add closing functionality
                const closeBtn = viewUserModal.querySelector('.close');
                closeBtn.onclick = function() {
                    viewUserModal.style.display = 'none';
                };
                
                // Close when clicking outside the modal
                window.onclick = function(event) {
                    if (event.target === viewUserModal) {
                        viewUserModal.style.display = 'none';
                    }
                };
            } else {
                console.error('User row not found for ID:', user_id);
                alert('Error: User data not found!');
            }
        }

        // First, modify the editUser function to properly handle the user data
        function editUser(user_id) {
            const userRow = document.querySelector(`tr[data-id="${user_id}"]`);
            
            if (userRow) {
                const cells = userRow.getElementsByTagName('td');
                const userName = cells[1].textContent; // Name is in second column
                const userEmail = cells[2].textContent; // Email is in third column
                const userRole = cells[3].textContent; // Role is in fourth column
                
                // Set the current user ID for the form submission
                currentUserId = user_id;
                
                // Populate the form fields
                document.getElementById('userName').value = userName;
                document.getElementById('userEmail').value = userEmail;
                // Set the role value based on the text
                const roleSelect = document.getElementById('userRole');
                if (userRole === 'Superadmin') {
                    roleSelect.value = '1';
                } else if (userRole === 'Regular Admin') {
                    roleSelect.value = '2';
                } else {
                    roleSelect.value = '3';
                }
                
                // Update modal title and display it
                document.getElementById('userModalTitle').innerText = 'Edit User';
                userModal.style.display = 'block';
            } else {
                console.error('User row not found for ID:', user_id);
                alert('Error: User data not found!');
            }
        }

        // Modify the form submission handler to properly handle both add and edit
        document.getElementById('userForm').onsubmit = function(event) {
            event.preventDefault();

            const name = document.getElementById('userName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const role = document.getElementById('userRole').value.trim();
            const password = document.getElementById('userPassword').value.trim();

            if (!name || !email || !role) {
                alert('Please fill in all required fields.');
                return;
            }

            // Create FormData object
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('role', role);
            
            // Only include password if it's provided (for editing)
            if (password) {
                formData.append('password', password);
            }

            // If we have a currentUserId, this is an edit operation
            if (currentUserId) {
                formData.append('user_id', currentUserId);
                formData.append('action', 'edit');
            } else {
                formData.append('action', 'add');
            }

            // Send to the appropriate endpoint
            const url = currentUserId ? 'edit_user.php' : 'add_user.php';
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    alert(currentUserId ? 'User updated successfully!' : 'User added successfully!');
                    fetchAndDisplayUsers(currentPage);
                    userModal.style.display = 'none';
                    userForm.reset();
                    currentUserId = null;
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => {
                console.error('Error saving user:', error);
                alert('Error saving user. Please try again.');
            });
        };

        // Delete User Function
        function deleteUser(user_id) {
            const confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                fetch('delete_for_fetch_users.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${user_id}`, // Send user_id as POST data
                })
                .then(response => response.text())  // Expect a response as text
                .then(data => {
                    console.log(data);  
                    if (data.includes('User deleted successfully')) {
                        alert("User deleted successfully!");
                        fetchAndDisplayUsers(currentPage); 
                    } else {
                        alert("Error deleting user: "+ data);
                    }
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                });
            }
        }

        

        // // Update the fetch_users.php query to include the status
        function fetchAndDisplayUsers(page) {
            console.log(`Fetching users for page: ${page}`);
            
            fetch(`fetch_users.php?page=${page}&usersPerPage=${usersPerPage}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched users after pagination:', data);
                    
                    totalPages = data.totalPages || 1;
                    currentPage = page;  // Update current page
                    
                    users = data.users || []; 
                    
                    updateTable(); // Update the table with new users
                    updatePagination(); // Update the pagination controls
                })
                .catch(error => {
                    alert('Reload the page to see the changes you have made.');
                });
        }


        console.log(`Fetching users for page: ${page}`);

        function updatePagination() {
            console.log(`Updating pagination: Current Page ${currentPage}, Total Pages ${totalPages}`);
            
            pageInfoEl.textContent = `Page ${currentPage} of ${totalPages}`;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage >= totalPages;
        }

        // Modify updatTable function to include status
        function updateTable() {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';  
            users.forEach(user => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', user.user_id);

                const approveButton = user.status === 'pending' 
                    ? '<button onclick="approveUser(${user.user_id})">Approve</button>'
                    : '';

                // Adding user details to the row
                row.innerHTML = `
                    <td>${user.user_id}</td>
                    <td>${user.fname} ${user.lname}</td>
                    <td>${user.email}</td>
                    <td>${user.role === 1 ? 'Superadmin' : 'Regular Admin'}</td>
                    <td>${new Date(user.created_at).toLocaleString()}</td>
                    <td>
                        <button onclick="viewUser(${user.user_id})">View</button>
                        <button onclick="editUser(${user.user_id})">Edit</button>
                        <button onclick="deleteUser(${user.user_id})">Delete</button>
                        <button onclick="approveUser(${user.user_id})">Approve</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Add the approveUser function
        function approveUser(user_id) {
            if (confirm('Are you sure you want to approve this user?')) {
                fetch('approve_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${user_id}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('successfully')) {
                        alert('User approved successfully!');
                        fetchAndDisplayUsers(currentPage);
                    } else {
                        alert('Error approving user: ' + data);
                    }
                })
            }
        }

        window.onclick = function(event) {
            if (event.target === userModal || event.target === viewUserModal) {
                userModal.style.display = 'none';
                viewUserModal.style.display = 'none';
            }
        };

    </script>
</body>
</html>




