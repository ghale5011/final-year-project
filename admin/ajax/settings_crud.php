<?php
require('../inc/db_config.php'); // Include database configuration
require('../inc/essentials.php'); // Include essential functions

adminLogin(); // Ensure the admin is logged in before processing requests

// Fetch General Settings
if (isset($_POST['get_general'])) {
    $q = "SELECT * FROM `settings` WHERE `sr_no` = ?";
    $values = [1];
    $res = select($q, $values, "i");
    $data = mysqli_fetch_assoc($res);
    echo json_encode($data); // Return settings data as JSON
} 

// Update General Settings
if (isset($_POST['upd_general'])) {
    $frm_data = filteration($_POST); // Sanitize input data

    $q = "UPDATE `settings` SET `site_title` = ?, `site_about` = ? WHERE `sr_no` = ?";
    $values = [$frm_data['site_title'], $frm_data['site_about'], 1]; 
    $res = update($q, $values, 'ssi'); // Execute update query
    echo $res; // Return update status
}

// Toggle Website Shutdown Mode
if (isset($_POST['upd_shutdown'])) {
    $frm_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0; // Toggle shutdown value

    $q = "UPDATE `settings` SET `shutdown` = ? WHERE `sr_no` = ?";
    $values = [$frm_data, 1]; 
    $res = update($q, $values, 'ii'); // Execute update query
    echo $res; // Return update status
}

// Fetch Contact Details
if (isset($_POST['get_contacts'])) {
    $q = "SELECT * FROM `contact_details` WHERE `sr_no` = ?";
    $values = [1];
    $res = select($q, $values, "i");
    $data = mysqli_fetch_assoc($res);
    echo json_encode($data); // Return settings data as JSON
} 

// Update Contact Details
if (isset($_POST['upd_contacts'])) 
{
    $frm_data = filteration($_POST); // Sanitize input data

    $q = "UPDATE `contact_details` SET `address`=? ,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`insta`=?,`tw`=?,`iframe`=? WHERE `sr_no` = ?";
    $values = [$frm_data['address'], $frm_data['gmap'], $frm_data['pn1'], $frm_data['pn2'], $frm_data['email'], $frm_data['fb'], $frm_data['insta'], $frm_data['tw'], $frm_data['iframe'], 1];
    $res = update($q, $values, 'sssssssssi'); // Execute update query
    echo $res; // Return update status
}


// Upload and Update About Us Image
if (isset($_POST['add_member']))
{
    $frm_data = filteration($_POST); // Sanitize input data

    $img_r = uploadImage($_FILES['picture'],ABOUT_FOLDER); // Upload image

    if($img_r == 'inv_img'){
        echo $img_r;
    }
    else if($img_r == 'inv_size'){
        echo $img_r;
    }
    else if($img_r == 'upd_failed'){
        echo $img_r;
    }
    else{
        $q = "INSERT INTO `team_details`(`name`, `picture`) VALUES(?,?)";
        $values = [$frm_data['name'], $img_r];
        $res = insert($q, $values, 'ss');
        echo $res; // Return insert status
    }
}
    
// Fetch and Display Team Members
if(isset($_POST['get_members']))
{
        $res = selectAll('team_details');

        while($row = mysqli_fetch_assoc($res))
        {
            $path = ABOUT_IMG_PATH;
            echo <<<data
            <div class="col-md-2 mb-3">
                <div class="card bg-dark text-white">
                <img src="$path$row[picture]" class="card-img">
                <div class="card-img-overlay text-end">
                    <button type="button" onclick="rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                    <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
                <p class="card-text text-center px-3 py-2">$row[name]</p>
                </div>
            </div>
            data;
        }
    }

    // Delete Team Member
    if(isset($_POST['rem_member']))
    {
        $frm_data = filteration($_POST); // Sanitize input data
        $values = [$frm_data['rem_member']];

        $pre_q = "SELECT * FROM `team_details` WHERE `sr_no` = ?";
        $res = select($pre_q, $values, 'i');
        $img = mysqli_fetch_assoc($res); // Return delete status

        if(deleteImage($img['picture'],ABOUT_FOLDER)){
            $q = "DELETE FROM `team_details` WHERE `sr_no` = ?";
            $res = delete($q, $values, 'i'); 
            echo $res; // Delete
        }
        else{
            echo 0; // Return failure status
        }
    }
    ?>
