<?php

// Include necessary files for database connection, essentials, and Khalti configuration
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
require('inc/khalti/config_khalti.php');

date_default_timezone_set("Asia/Kathmandu");

// Start a session to manage user session variables
session_start();

// Unset the room session data after payment (cleanup)
unset($_SESSION['room']);

// Function to regenerate session for a logged-in user using their user ID
function regenrate_session($uid)
{
    // Fetch user data from the database based on the user ID
    $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$uid], 'i');
    $user_fetch = mysqli_fetch_assoc($user_q);

    // Set session variables for the logged-in user
    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] = $user_fetch['name'];
    $_SESSION['uPic'] = $user_fetch['profile'];
    $_SESSION['uPhone'] = $user_fetch['phonenum'];
}



// Check if the required GET parameters are set (pidx and purchase_order_id)
if (isset($_GET['pidx']) && isset($_GET['purchase_order_id'])) {
    // Get payment index (pidx) and order ID from the URL
    $pidx = $_GET['pidx'];
    $order_id = $_GET['purchase_order_id'];

    // Prepare the data to verify payment with Khalti's API
    $data = [
        'pidx' => $pidx  // Payment index for verification
    ];

    // Set the headers for the API request
    $headers = [
        "Authorization: Key " . KHALTI_SECRET_KEY,  // Khalti secret key for authentication
        "Content-Type: application/json"  // Content type for the request
    ];

    // Initialize cURL for making the API request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, KHALTI_VERIFY_PAYMENT_URL);  // Khalti verification API endpoint
    curl_setopt($ch, CURLOPT_POST, 1);  // Set request method to POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Send the data in JSON format
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Set the headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response instead of printing

    // Execute the cURL request and capture the response
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // Get the HTTP status code of the response
    curl_close($ch);

    // If the request is successful (status code 200), handle the response
    if ($status_code == 200) {
        // Decode the JSON response from Khalti
        $response_data = json_decode($response, true);



        // Check if the payment status is 'Completed'
        if ($response_data['status'] === "Completed") {
            // Get the transaction details from the response
            $txn_id = $response_data['transaction_id'];
            $amount = $response_data['total_amount'];


            // Fetch the booking details using the order ID
            $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`=?";
            $slct_res = select($slct_query, [$order_id], 's');

            // If no booking found for the order ID, redirect to homepage
            if (mysqli_num_rows($slct_res) == 0) {
                redirect('index.php');
            }

            // Fetch the booking details
            $slct_fetch = mysqli_fetch_assoc($slct_res);

            // Regenerate session if the user is not logged in
            if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                regenrate_session($slct_fetch['user_id']);
            }



            // Update the booking order status in the database
            $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',
                `trans_id`=?, `trans_amt`=?, `trans_status`='TXN_SUCCESS', 
                `trans_resp_msg`='Payment verified successfully' 
                WHERE `booking_id`=?";
            insert($upd_query, [$txn_id, $amount / 100, $slct_fetch['booking_id']], 'ssi');

            // Redirect to the payment success page with the order ID and status
            redirect('pay_status.php?order=' . $order_id . '&status=success');
        } else {


            // Handle payment failure
            $error_message = 'Payment verification failed. Status: ' . $response_data['status'];

            // Fetch booking details using the order ID
            $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`=?";
            $slct_res = select($slct_query, [$order_id], 's');

            // If booking found, update payment status to failed
            if (mysqli_num_rows($slct_res) > 0) {
                $slct_fetch = mysqli_fetch_assoc($slct_res);

                $upd_query = "UPDATE `booking_order` SET `booking_status`='payment failed',
                    `trans_status`='TXN_FAILED', `trans_resp_msg`=? 
                    WHERE `booking_id`=?";
                insert($upd_query, [$error_message, $slct_fetch['booking_id']], 'si');
            }

            // Redirect to the payment failure page with the order ID and status
            redirect('pay_status.php?order=' . $order_id . '&status=failed');
        }
    } else {

        
        // Handle API error if payment verification request fails
        $error_message = 'Error verifying payment with Khalti.';

        // Fetch booking details using the order ID
        $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`=?";
        $slct_res = select($slct_query, [$order_id], 's');

        // If booking found, update payment status to failed due to API error
        if (mysqli_num_rows($slct_res) > 0) {
            $slct_fetch = mysqli_fetch_assoc($slct_res);

            $upd_query = "UPDATE `booking_order` SET `booking_status`='payment failed',
                `trans_status`='TXN_FAILED', `trans_resp_msg`=? 
                WHERE `booking_id`=?";
            insert($upd_query, [$error_message, $slct_fetch['booking_id']], 'si');
        }

        // Redirect to the payment failure page with the order ID and status
        redirect('pay_status.php?order=' . $order_id . '&status=failed');
    }
} else {
    // If pidx or order ID is missing in the URL, redirect to homepage
    redirect('index.php');
}
?>
