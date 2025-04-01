<?php

require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
require('inc/khalti/config_khalti.php');

date_default_timezone_set("Asia/Kathmandu");
// Start a session to track user login and other session data
session_start();

// Check if the user is logged in; if not, redirect to the home page
if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
  redirect('index.php');
}

// Check if the 'pay_now' button was clicked (form submission)
if (isset($_POST['pay_now'])) {

  // Generate a unique order ID using the user's ID and a random number
  $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(11111, 9999999);
  $CUST_ID = $_SESSION['uId'];// Set the customer ID (user ID from the session)
  $TXN_AMOUNT = $_SESSION['room']['payment']; // Amount in NPR (e.g., 1000 = Rs. 1000)

  // Insert payment data into the database
  $frm_data = filteration($_POST);

  // Insert booking order data into the database (booking order details)
  $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`) VALUES (?,?,?,?,?)";
  insert($query1, [$CUST_ID, $_SESSION['room']['id'], $frm_data['checkin'], $frm_data['checkout'], $ORDER_ID], 'issss');

  // Get the last inserted booking ID to use in the booking details table
  $booking_id = mysqli_insert_id($con);

  // Insert booking details data into the database (user's booking details like name, phone number, and address)
  $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
  insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phonenum'], $frm_data['address']], 'issssss');

  // Prepare the data for Khalti payment initiation
  $data = [
    'return_url' => RETURN_URL,
    'website_url' => WEBSITE_URL,
    'amount' => $TXN_AMOUNT * 100,
    'purchase_order_id' => $ORDER_ID,
    'purchase_order_name' => 'Hotel Booking',
  ];

  // Set headers for the API request (Authorization and Content-Type)
  $headers = [
    "Authorization: Key " . KHALTI_SECRET_KEY,
    "Content-Type: application/json"
  ];

  // Initialize cURL for making the API request to Khalti
  $ch = curl_init();
  // Set cURL options
  curl_setopt($ch, CURLOPT_URL, KHALTI_INTIIATE_PAYMENT_URL);
  curl_setopt($ch, CURLOPT_POST, 1);// Set the request method to POST
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Send the data in the request body as JSON
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers for the request
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of printing it

  // Execute the cURL request and capture the response
  $response = curl_exec($ch);
  // Get the HTTP status code from the response
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  // If the payment initiation request was successful (status code 200)
  if ($status_code == 200) {
    $response_data = json_decode($response, true);// Decode the JSON response from Khalti API

    // Extract the payment URL from the response
    $payment_url = $response_data['payment_url']; // URL to redirect the user for payment
   // Redirect the user to the Khalti payment page
    header("Location: $payment_url");
    exit;
  } else {
    echo "Error initiating payment. Please try again.";
  }
}
