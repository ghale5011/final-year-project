<?php
    // Ensure the admin is logged in before accessing settings
    require('inc/essentials.php'); // Include essential functions
    require('inc/db_config.php'); // Include database configuration
    adminLogin(); // Check if the admin is logged in
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users</title>
    <!-- Include necessary stylesheets and scripts -->
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

<!-- Include the navigation header -->
<?php require('inc/header.php'); ?>

<!-- Main Content Section -->
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">USERS</h3>

            <!-- Users Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    
                    <!-- Search Bar for Users -->
                    <div class="text-end mb-4">
                    <input type="text" oninput="search_user(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
                    </div>

                    <!-- Table to display users -->
                    <div class="table-responsive">
                        <table class="table table-hover border text-center" style="min-width: 1300px;">
                            <thead>
                                <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone no.</th>
                                <th scope="col">Location</th>
                                <th scope="col">DOB</th>
                                <th scope="col">Verified</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <!-- Users data will be dynamically inserted here -->                            
                             <tbody id="users-data">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


<!-- Include scripts -->
<?php require('inc/scripts.php'); ?>
<script src="scripts/users.js"></script>

</body>
</html>