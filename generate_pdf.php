<?php 

  require('admin/inc/essentials.php');
  require('admin/inc/db_config.php');
  require('admin/inc/mpdf/vendor/autoload.php');

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  if(isset($_GET['gen_pdf']) && isset($_GET['id']))
  {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*, uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con,$query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
      header('location: index.php');
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("h:ia | d-m-Y",strtotime($data['datentime']));
    $checkin = date("d-m-Y",strtotime($data['check_in']));
    $checkout = date("d-m-Y",strtotime($data['check_out']));

    $table_data = "
    <style>
      body {
        font-family: 'Arial', sans-serif;
        margin: 30px;
        color: #333;
        background-color: #f9f9f9;
      }
      h2 {
        text-align: center;
        color: #4CAF50;
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: bold;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      }
      table, th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
      }
      th {
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        border-bottom: 3px solid #ddd;
      }
      td {
        font-size: 14px;
        color: #555;
      }
      tr:nth-child(even) {
        background-color: #f2f2f2;
      }
      tr:nth-child(odd) {
        background-color: #ffffff;
      }
      .status {
        font-weight: bold;
        color: #ff5722;
        font-size: 16px;
      }
      .refund-status {
        color: #00bcd4;
        font-weight: bold;
      }
      .table-header {
        background-color: #f1f1f1;
        font-weight: bold;
      }
      .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 12px;
        color: #777;
      }
      .footer a {
        color: #4CAF50;
        text-decoration: none;
      }
    </style>

    <h2>Booking Receipt</h2>

    <table>
      <tr>
        <td><strong>Order ID:</strong> $data[order_id]</td>
        <td><strong>Booking Date:</strong> $date</td>
      </tr>
      <tr>
        <td colspan='2' class='status'><strong>Status:</strong> $data[booking_status]</td>
      </tr>
      <tr>
        <td><strong>Name:</strong> $data[user_name]</td>
        <td><strong>Email:</strong> $data[email]</td>
      </tr>
      <tr>
        <td><strong>Phone Number:</strong> $data[phonenum]</td>
        <td><strong>Address:</strong> $data[address]</td>
      </tr>
      <tr>
        <td><strong>Room Name:</strong> $data[room_name]</td>
        <td><strong>Cost:</strong> Rs.$data[price] per night</td>
      </tr>
      <tr>
        <td><strong>Check-in:</strong> $checkin</td>
        <td><strong>Check-out:</strong> $checkout</td>
      </tr>
    ";

    if($data['booking_status']=='cancelled')
    {
      $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";
      $table_data.="<tr>
        <td><strong>Amount Paid:</strong> Rs.$data[trans_amt]</td>
        <td><strong>Refund:</strong> <span class='refund-status'>$refund</span></td>
      </tr>";
    }
    else if($data['booking_status']=='payment failed')
    {
      $table_data.="<tr>
        <td><strong>Transaction Amount:</strong> Rs.$data[trans_amt]</td>
        <td><strong>Failure Response:</strong> $data[trans_resp_msg]</td>
      </tr>";
    }
    else
    {
      $table_data.="<tr>
        <td><strong>Room Number:</strong> $data[room_no]</td>
        <td><strong>Amount Paid:</strong> Rs.$data[trans_amt]</td>
      </tr>";
    }

    $table_data.="</table>";

    // Footer
    $table_data.="
    <div class='footer'>
      <p>Thank you for choosing our service. For more information, visit <a href='http://127.0.0.1/hbwebsite/index.php'>our website</a>.</p>
    </div>";

    // Create PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($table_data);
    $mpdf->Output($data['order_id'].'.pdf','D');

  }
  else{
    header('location: index.php');
  }

?>
