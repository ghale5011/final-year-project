    <?php
    // Include essential utility functions and database configuration
    require('inc/essentials.php');
    require('inc/db_config.php');

    // Check if the admin is logged in, redirect if not
    adminLogin();

    // Mark queries as seen (read)
    if(isset($_GET['seen']))
    {
        $frm_data = filteration($_GET); // Sanitize GET input

        if($frm_data['seen']=='all'){
        // Update all queries to 'seen' status
        $q = "UPDATE `user_queries` SET `seen`=?";
        $values = [1];
        if(update($q,$values,'i')){
            alert('success','Marked all as read!');
        }
        else{
            alert('error','Operation Failed!');
        }
        }
        else{
        // Update a specific query to 'seen' status
        $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
        $values = [1,$frm_data['seen']];
        if(update($q,$values,'ii')){
            alert('success','Marked as read!');
        }
        else{
            alert('error','Operation Failed!');
        }
        }
    }

    // Delete user queries
    if(isset($_GET['del']))
    {
        $frm_data = filteration($_GET); // Sanitize GET input

        if($frm_data['del']=='all'){
        // Delete all queries from the database
        $q = "DELETE FROM `user_queries`";
        if(mysqli_query($con,$q)){
            alert('success','All data deleted!');
        }
        else{
            alert('error','Operation failed!');
        }
        }
        else{
        // Delete a specific query
        $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
        $values = [$frm_data['del']];
        if(delete($q,$values,'i')){
            alert('success','Data deleted!');
        }
        else{
            alert('error','Operation failed!');
        }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
    
    <?php require('inc/links.php'); // Include CSS and other external files ?>
    </head>
    <body class="bg-light">

    <?php require('inc/header.php'); // Include the navigation header ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">USER QUERIES</h3>

            <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <!-- Action buttons for marking all queries as read and deleting all -->
                <div class="text-end mb-4">
                <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                    <i class="bi bi-check-all"></i> Mark all read
                </a>
                <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                    <i class="bi bi-trash"></i> Delete all
                </a>
                </div>

                <!-- Table displaying user queries -->
                <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
                <table class="table table-hover border">
                    <thead class="sticky-top">
                    <tr class="bg-dark text-light">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col" width="20%">Subject</th>
                        <th scope="col" width="30%">Message</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        // Fetch user queries from the database
                        $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC";
                        $data = mysqli_query($con,$q);
                        $i=1;

                        while($row = mysqli_fetch_assoc($data))
                        {
                        $date = date('d-m-Y',strtotime($row['date'])); // Format the date
                        $seen='';

                        // Check if the query is unread and add "Mark as read" button
                        if($row['seen']!=1){
                            $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary'>Mark as read</a> <br>";
                        }

                        // Add delete button
                        $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2'>Delete</a>";

                        // Display query details in the table
                        echo<<<query
                            <tr>
                            <td>$i</td>
                            <td>$row[name]</td>
                            <td>$row[email]</td>
                            <td>$row[subject]</td>
                            <td>$row[message]</td>
                            <td>$date</td>
                            <td>$seen</td>
                            </tr>
                        query;
                        $i++;
                        }
                    ?>
                    </tbody>
                </table>
                </div>

            </div>
            </div>

        </div>
        </div>
    </div>
    
    <?php require('inc/scripts.php'); // Include JavaScript files ?>

    </body>
    </html>
