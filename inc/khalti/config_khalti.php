<?php
    // Define Khalti API secret key for authentication in payment requests
    define('KHALTI_SECRET_KEY', 'bf3908eef67d4f2bafd02e33838f7f33'); // Secret key for server-side authentication

    // Define Khalti API public key for use in client-side requests
    define('KHALTI_PUBLIC_KEY', 'a4e2c58638b346bfa52aff31b08fa8b1'); // Public key for payment integration on frontend

    // URL for initiating payment through Khalti API (for the user's payment request)
    define('KHALTI_INTIIATE_PAYMENT_URL', 'https://dev.khalti.com/api/v2/epayment/initiate/'); // Endpoint to initiate the payment

    // URL for verifying payment after the user has made the payment
    define('KHALTI_VERIFY_PAYMENT_URL', 'https://dev.khalti.com/api/v2/epayment/lookup/'); // Endpoint to verify payment status

    // URL to redirect the user after the payment process is complete (success or failure)
    define('RETURN_URL', 'http://127.0.0.1/hbwebsite/pay_response.php'); // URL where the user will be redirected after payment

    // URL of the website (used for reference in the payment request)
    define('WEBSITE_URL', 'http://127.0.0.1/hbwebsite/'); // Base URL of your website
?>
