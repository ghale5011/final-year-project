<?php
// Include necessary files for database configuration and essential functions
require('../inc/db_config.php');
require('../inc/essentials.php');

// Ensure the admin is logged in before processing any requests
adminLogin();

// Upload and Update About Us Image
if (isset($_POST['add_feature']))
{
    // Sanitize input data to prevent SQL injection
    $frm_data = filteration($_POST);

    // SQL query to insert a new feature into the 'features' table
    $q = "INSERT INTO `features`(`name`) VALUES (?)";
    $values = [$frm_data['name']];
    $res = insert($q, $values, 's'); // Execute the insert query
    echo $res; // Return the result of the insert operation
}

// Fetch and display all features from the database
if(isset($_POST['get_features']))
{
    // Select all records from the 'features' table
    $res = selectAll('features');
    $i = 1; // Counter for serial number
    
    // Loop through each feature and display it in a table row
    while($row = mysqli_fetch_assoc($res))
    {
        echo <<<data
            <tr>
                <td>$i</td>
                <td>$row[name]</td>
                <td>
                    <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
}    

// Delete a feature from the database
if(isset($_POST['rem_feature']))
{
    // Sanitize input data to prevent SQL injection
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_feature']];

    $check_q = select('SELECT * FROM `room_features` WHERE `features_id` = ?', [$frm_data['rem_feature']], 'i');
    if(mysqli_num_rows($check_q) == 0){
        // SQL query to delete the feature from the 'features' table and from the 'room_features' table if not associated with any room
        $q = "DELETE FROM `features` WHERE `id`= ?";
        $res = delete($q, $values, 'i');  // Execute the delete query
        echo $res; // Return the result of the delete operation
    }
    else{
        echo 'room_added'; // Return error if the feature is associated with a room
    }
}

// Add a new facility to the database
if (isset($_POST['add_facility']))
{
    // Sanitize input data to prevent SQL injection
    $frm_data = filteration($_POST);

    // Upload the SVG image for the facility
    $img_r = uploadSVGImage($_FILES['icon'],FACILITIES_FOLDER);

    // Check the result of the image upload
    if($img_r == 'inv_img'){
        echo $img_r; // Return error if the image is invalid
    }
    else if($img_r == 'inv_size'){
        echo $img_r; // Return error if the image size is invalid
    }
    else if($img_r == 'upd_failed'){
        echo $img_r; // Return error if the upload failed
    }
    else{
        // SQL query to insert a new facility into the 'facilities' table
        $q = "INSERT INTO `facilities`(`icon`,`name`, `description`) VALUES(?,?,?)";
        $values = [$img_r, $frm_data['name'], $frm_data['desc']];
        $res = insert($q, $values, 'sss'); // Execute the insert query
        echo $res; // Return the result of the insert operation
    }
}

// Fetch and display all facilities from the database
if(isset($_POST['get_facilities']))
{
    // Select all records from the 'facilities' table
    $res = selectAll('facilities');
    $i = 1; // Counter for serial number
    $path = FACILITIES_IMG_PATH; // Path to the facility images
    
    // Loop through each facility and display it in a table row
    while($row = mysqli_fetch_assoc($res))
    {
        echo <<<data
            <tr class = 'align-middle'>
                <td>$i</td>
                <td><img src="$path$row[icon]" width="60px"></td>
                <td>$row[name]</td>
                <td>$row[description]</td>
                <td>
                    <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
}

// Delete a facility from the database
if(isset($_POST['rem_facility']))
{
    // Sanitize input data to prevent SQL injection
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_facility']];

    $check_q = select('SELECT * FROM `room_facilities` WHERE `facilities_id` = ?', [$frm_data['rem_facility']], 'i');

    if(mysqli_num_rows($check_q) ==0)
    {
        // SQL query to select the facility by its ID
        $pre_q = "SELECT * FROM `facilities` WHERE `id` = ?";
        $res = select($pre_q, $values, 'i');
        $img = mysqli_fetch_assoc($res); // Fetch the facility data

        // Delete the facility image from the server
        if(deleteImage($img['icon'],FACILITIES_FOLDER)){
            // SQL query to delete the facility from the 'facilities' table
            $q = "DELETE FROM `facilities` WHERE `id`= ?"; 
            $res = delete($q, $values, 'i'); // Execute the delete query
            echo $res;
        }
        else{
            echo 0; // Return error if the image deletion fails
        }
    }
    else{
        echo 'room_added'; // Return error if the facility is associated with a room
    }
}
?>