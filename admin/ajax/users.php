<?php
    // Include necessary files for database configuration and essential functions
    require('../inc/db_config.php');
    require('../inc/essentials.php');

    // Ensure the admin is logged in before processing any requests
    adminLogin();

    // Handle the request to fetch all users
    if (isset($_POST['get_users']))
    {
      // Fetch all users from the database
        $res = selectAll('user_cred'); // Function to select all rows from the 'user_cred' table
        $i=1; // Counter for serial number
        $path = USERS_IMG_PATH; // Path to user profile images

        $data="";  // Variable to store HTML table rows
        while($row = mysqli_fetch_assoc($res))
        {
          // Delete button for unverified users
            $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
                <i class='bi bi-trash'></i> 
            </button>";
            // Verification status badge
            $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";// Default: Not verified

            if($row['is_verified']){
                $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
                $del_btn = ""; // Remove delete button for verified users
            }
            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>
                active
            </button>";

            // User status button (active/inactive)
            if(!$row['status']){
                $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>
                  inactive
                </button>";
            }
            $date = date("d-m-Y",strtotime($row['datentime']));

            // Build the table row for the current user
            $data.="
                <tr>
                    <td>$i</td>
                    <td>
                        <img src='$path$row[profile]' width='55px'>
                        <br>
                        $row[name]
                    </td>
                    <td>$row[email]</td>
                    <td>$row[phonenum]</td>
                    <td>$row[address] | $row[pincode]</td>
                    <td>$row[dob]</td>
                    <td>$verified</td>
                    <td>$status</td>
                    <td>$date</td>
                    <td>$del_btn</td>
                </tr>
            ";
            $i++; // Increment serial number
        }

        echo $data;// Output the table rows
    }

    // Handle the request to toggle user status (active/inactive)
    if(isset($_POST['toggle_status']))
    {
        $frm_data = filteration($_POST);
        // Query to update user status
        $q = "UPDATE `user_cred` SET `status`=? WHERE `id`=?";
        $v = [$frm_data['value'],$frm_data['toggle_status']];
        
        // Execute the query
        if(update($q,$v,'ii')){
        echo 1;
        }
        else{
        echo 0;
        }
    }


    // Handle the request to remove a user
    if(isset($_POST['remove_user']))
    {
      $frm_data = filteration($_POST);
      
      // Query to delete an unverified user
      $res = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?",[$frm_data['user_id'],0],'ii');
      // Check if the deletion was successful
      if($res){
        echo 1;
      }
      else{
        echo 0;
      }
  
    }


    // Handle the request to search for users by name
    if(isset($_POST['search_user']))
    {
      $frm_data = filteration($_POST);
      
      // Query to search for users by name in the 'user_cred' table
      $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";
  
      $res = select($query,["%$frm_data[name]%"],'s');  // Execute the query with a search pattern 
      $i=1; // Counter for serial number
      $path = USERS_IMG_PATH;
  
      $data = ""; // Variable to store HTML table rows
  
      while($row = mysqli_fetch_assoc($res))
      {
        // Delete button for unverified users
        $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
          <i class='bi bi-trash'></i> 
        </button>";
        
        // Verification status badge
        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";  // Default: Not verified
  
        if($row['is_verified']){
          $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
          $del_btn = ""; // Remove delete button for verified users
        }
        // User status button (active/inactive)
        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>
          active
        </button>";
  
        if(!$row['status']){
          $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>
            inactive
          </button>";
        }
        
        $date = date("d-m-Y",strtotime($row['datentime']));
        // Build the table row for the current user
        $data.="
          <tr>
            <td>$i</td>
            <td>
              <img src='$path$row[profile]' width='55px'>
              <br>
              $row[name]
            </td>
            <td>$row[email]</td>
            <td>$row[phonenum]</td>
            <td>$row[address] | $row[pincode]</td>
            <td>$row[dob]</td>
            <td>$verified</td>
            <td>$status</td>
            <td>$date</td>
            <td>$del_btn</td>
          </tr>
        ";
        $i++;
      }
  
      echo $data;
    }

?>