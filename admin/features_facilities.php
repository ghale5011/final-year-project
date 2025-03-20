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
    <title>Admin Panel - Features & Facilities</title>
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
            <h3 class="mb-4">FEATURES & FACILITIES</h3>

            <!-- Features Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Features</h5>
                        <!-- Button to open the modal for adding a new feature -->
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                            <i class="bi bi-file-plus"></i> Add
                        </button>
                    </div>

                    <!-- Table to display features -->
                    <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="features_data">
                                <!-- Features data will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Facilities Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Facilities</h5>
                        <!-- Button to open the modal for adding a new facility -->
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#facility-s">
                            <i class="bi bi-file-plus"></i> Add
                        </button>
                    </div>

                    <!-- Table to display facilities -->
                    <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Icon</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" width="40%">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="facilities_data">
                                <!-- Facilities data will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feature Modal -->
<div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Form for adding a new feature -->
        <form id="feature_s_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Feature</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="feature_name" class="form-control shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Buttons to Cancel or Submit Changes -->
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Facility Modal -->
<div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Form for adding a new facility -->
        <form id="facility_s_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Facility</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="facility_name" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon</label>
                        <input type="file" name="facility_icon" accept=".svg" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="facility_desc" class="form-control shadow-none" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Buttons to Cancel or Submit Changes -->
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include scripts -->
<?php require('inc/scripts.php'); ?>
<script src="scripts/features_facilities.js"></script>


</body>
</html>