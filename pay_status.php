<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Include external CSS and JS files (links.php) -->
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - BOOKING STATUS</title>
</head>
<body class="bg-light">

  <!-- Include the header (navigation, etc.) -->
  <?php require('inc/header.php'); ?>

  <div class="container">
    <div class="row">

      <!-- Page Heading for Payment Status -->
      <div class="col-12 my-5 mb-3 px-4">
        <h2 class="fw-bold">PAYMENT STATUS</h2>
      </div>

      <?php 
        // Sanitize the input data from the URL (e.g., order)
        $frm_data = filteration($_GET);

        // Check if the user is logged in, if not redirect to the homepage
        if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
          redirect('index.php');
        }

        // Query to check the booking details, ensure the booking status is not 'pending' and matches the order and user ID
        $booking_q = "SELECT bo.*, bd.* FROM `booking_order` bo 
          INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
          WHERE bo.order_id=? AND bo.user_id=? AND bo.booking_status!=?";

        // Execute the query
        $booking_res = select($booking_q,[$frm_data['order'],$_SESSION['uId'],'pending'],'sis');

        // If no booking record is found, redirect to the homepage
        if(mysqli_num_rows($booking_res) == 0){
          redirect('index.php');
        }

        // Fetch the booking data from the result set
        $booking_fetch = mysqli_fetch_assoc($booking_res);

        // Check the transaction status and display the appropriate message
        if($booking_fetch['trans_status'] == "TXN_SUCCESS")
        {
          // Display success message if the payment was successful
          echo<<<data
            <div class="col-12 px-4">
              <p class="fw-bold alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                Payment done! Booking successful.
                <br><br>
                <a href='bookings.php'>Go to Bookings</a>
              </p>
            </div>
          data;
        }
        else
        {
          // Display failure message if the payment was unsuccessful
          echo<<<data
            <div class="col-12 px-4">
              <p class="fw-bold alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Payment failed! $booking_fetch[trans_resp_msg]
                <br><br>
                <a href='bookings.php'>Go to Bookings</a>
              </p>
            </div>
          data;
        }

      ?>

    </div>
  </div>

  <!-- Include the footer (contact, links, etc.) -->
  <?php require('inc/footer.php'); ?>

</body>
</html>
