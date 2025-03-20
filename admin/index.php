<?php
// Including essential files for functionality and database connection
require('inc/essentials.php');
require('inc/db_config.php');

// Starting the session
session_start();

// Redirecting to dashboard if the admin is already logged in
if (isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true) {
    redirect('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>

    <!-- Including external CSS and JS files -->
    <?php require('inc/links.php'); ?>

    <style>
        /* Styling for the login form container */
        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Login form container -->
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="post">
            <!-- Header section of the form -->
            <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
                <!-- Input field for Admin Name -->
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
                </div>
                <!-- Input field for Password -->
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <!-- Submit button for login -->
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
            </div>
        </form>
    </div>

    <?php 
    // Handling the login form submission
    if (isset($_POST['login'])) {
        // Filtering input data to prevent XSS and SQL injection
        $frm_data = filteration($_POST);

        // SQL query to validate admin credentials
        $query = "SELECT * FROM `admin_cred` WHERE `admin_name` = ? AND `admin_pass` = ?";
        $values = [$frm_data['admin_name'], $frm_data['admin_pass']];

        // Executing the query with parameter binding
        $res = select($query, $values, "ss");
        
        // Checking if credentials are valid
        if ($res->num_rows == 1) {
            // If credentials are valid, store session data and redirect to dashboard
            $row = mysqli_fetch_assoc($res);
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['sr_no'];
            // Redirecting to dashboard
            redirect('dashboard.php');
        } else {
            // If credentials are invalid, show an error alert
            alert('error', 'Login failed- Invalid Credentials!');
        }
    }
    ?>

    <!-- Including external script files -->
    <?php require('inc/scripts.php'); ?>
</body>

</html>
