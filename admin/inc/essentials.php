<?php

    //frontend purposes data

    define('SITE_URL', 'http://127.0.0.1/hbwebsite/'); // Define the site URL
    define('ABOUT_IMG_PATH', SITE_URL.'images/about/') ; // Define the path to About images
    define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/') ; // Define the path to Carousel images
    define('FACILITIES_IMG_PATH', SITE_URL.'images/facilities/') ; // Define the path to Features images
    define('ROOMS_IMG_PATH', SITE_URL.'images/rooms/') ; // Define the path to Room images
    define('USERS_IMG_PATH', SITE_URL.'images/users/') ; // Define the path to Users images


    //backend upload process needs the data
    define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/hbwebsite/images/'); // Define the path to upload images
    define('ABOUT_FOLDER', 'about/');
    define('CAROUSEL_FOLDER', 'carousel/');
    define('FACILITIES_FOLDER', 'facilities/');
    define('ROOMS_FOLDER', 'rooms/');
    define('USERS_FOLDER', 'users/');

    // sendgrid api key
    define('SENDGRID_API_KEY',"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");
    define('SENDGRID_EMAIL',"sumanghale396@gmail.com");
    define('SENDGRID_NAME',"satkarhotel");

    // Function to ensure admin login and session management
    function adminLogin() {
        session_start(); // Start a new or resume an existing session

        // Check if admin is logged in; if not, redirect to the login page
        if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
            echo "<script>
                window.location.href='index.php';
            </script>";
            exit; // Stop further execution of the script
        }
    }

    // Function to redirect to a specified URL
    function redirect($url) {
        echo "<script>
            window.location.href='$url';
        </script>";
        exit;
    }

    // Function to display alert messages
    // Parameters:
    // $type: Type of alert ('success' or any other value for 'danger')
    // $msg: The message to display
    function alert($type, $msg) {
        // Determine the Bootstrap class for the alert based on the type
        $bs_class = ($type == "success") ? "alert-success" : "alert-danger";

        // Display the alert message with a close button
        echo <<<alert
            <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;
    }

    // Function to upload an image
    function uploadImage($image, $folder) {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp']; // Define valid image MIME types
        $img_mime = $image['type']; // Get the MIME type of the uploaded image

        // Check if the uploaded file has a valid MIME type
        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_image'; // Invalid image MIME or format
        }
        // Check if the file size is greater than 2MB
        else if (($image['size'] / (1024 * 1024)) > 2) {
            return 'inv_size'; // Invalid size greater than 2MB
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION); // Get the extension of the uploaded image
            $rname = 'IMG_' . random_int(11111, 99999) . ".$ext"; // Generate a random name for the image

            $img_path = UPLOAD_IMAGE_PATH . $folder .$rname; // Define the full path to save the image

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($image['tmp_name'], $img_path)) {
                return $rname; // Return the name of the uploaded image
            }
            else {
                return 'upd_failed'; // Return an error message if the image could not be uploaded
            }
        }
    }

    // Function to delete an image
    function deleteImage($image, $folder)
    {
        if(unlink(UPLOAD_IMAGE_PATH.$folder.$image)){
            return true; // Return true if the image is deleted successfully
            }else {
            return false; // Return false if the image could not be deleted successfully
        }
    }

    function uploadSVGImage($image, $folder) {
        $valid_mime = ['image/svg+xml']; // Define valid image MIME types
        $img_mime = $image['type']; // Get the MIME type of the uploaded image

        // Check if the uploaded file has a valid MIME type
        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_image'; // Invalid image MIME or format
        }
        // Check if the file size is greater than 1MB
        else if (($image['size'] / (1024 * 1024)) > 1) {
            return 'inv_size';  // Invalid size greater than 1MB
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION); // Get the extension of the uploaded image
            $rname = 'IMG_' . random_int(11111, 99999) . ".$ext"; // Generate a random name for the image

            $img_path = UPLOAD_IMAGE_PATH . $folder .$rname; // Define the full path to save the image

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($image['tmp_name'], $img_path)) {
                return $rname; // Return the name of the uploaded image
            }
            else {
                return 'upd_failed'; // Return an error message if the image could not be uploaded
            }
        }
    }

    function uploadUserImage($image)
    {
      $valid_mime = ['image/jpeg','image/png','image/webp'];
      $img_mime = $image['type'];
  
      if(!in_array($img_mime,$valid_mime)){
        return 'inv_img'; //invalid image mime or format
      }
      else
      {
        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".jpeg";
  
        $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;
  
        if($ext == 'png' || $ext == 'PNG') {
          $img = imagecreatefrompng($image['tmp_name']);
        }
        else if($ext == 'webp' || $ext == 'WEBP') {
          $img = imagecreatefromwebp($image['tmp_name']);
        }
        else{
          $img = imagecreatefromjpeg($image['tmp_name']);
        }
  
  
        if(imagejpeg($img,$img_path,75)){
          return $rname;
        }
        else{
          return 'upd_failed';
        }
      }
    }

?>