<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['get_bookings']))
  {
    $frm_data = filteration($_POST);

      // SQL query to get booking details based on the search criteria (order_id, phone number, or user name)
    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      AND (bo.booking_status=? AND bo.arrival=?) ORDER BY bo.booking_id ASC";

    // Execute the query with the search keyword, booking status 'booked', and arrival status '0' (not assigned yet)
    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","booked",0],'sssss');
    
    // Initialize variables for the table and row count
    $i=1;
    $table_data = "";

    // Check if any bookings were found, otherwise display a message and exit
    if(mysqli_num_rows($res)==0){
      echo"<b>No Data Found!</b>";
      exit;
    }

    // Loop through each booking record and prepare the table rows
    while($data = mysqli_fetch_assoc($res))
    {
      $date = date("d-m-Y",strtotime($data['datentime']));
      $checkin = date("d-m-Y",strtotime($data['check_in']));
      $checkout = date("d-m-Y",strtotime($data['check_out']));

      // Append each booking's data as a new row in the table
      $table_data .="
        <tr>
          <td>$i</td>
          <td>
            <span class='badge bg-primary'>
              Order ID: $data[order_id]
            </span>
            <br>
            <b>Name:</b> $data[user_name]
            <br>
            <b>Phone No:</b> $data[phonenum]
          </td>
          <td>
            <b>Room:</b> $data[room_name]
            <br>
            <b>Price:</b> Rs.$data[price]
          </td>
          <td>
            <b>Check-in:</b> $checkin
            <br>
            <b>Check-out:</b> $checkout
            <br>
            <b>Paid:</b> Rs.$data[trans_amt]
            <br>
            <b>Date:</b> $date
          </td>
          <td>
            <!-- Button to assign room -->
            <button type='button' onclick='assign_room($data[booking_id])' class='btn text-white btn-sm fw-bold custom-bg shadow-none' data-bs-toggle='modal' data-bs-target='#assign-room'>
              <i class='bi bi-check2-square'></i> Assign Room
            </button>
            <br>
            <!-- Button to cancel booking -->
            <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
              <i class='bi bi-trash'></i> Cancel Booking
            </button>
          </td>
        </tr>
      ";

      $i++;
    }

    echo $table_data;
  }

  // Handle the assignment of a room to a booking
  if(isset($_POST['assign_room']))
  {
    $frm_data = filteration($_POST);
    // SQL query to update booking details with room number and set arrival to 1 (assigned)
    $query = "UPDATE `booking_order` bo INNER JOIN `booking_details` bd
      ON bo.booking_id = bd.booking_id
      SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ? 
      WHERE bo.booking_id = ?";

    // Set the values for the update query (arrival = 1, rate_review = 0, room_no, booking_id)
    $values = [1,0,$frm_data['room_no'],$frm_data['booking_id']];

    $res = update($query,$values,'iisi'); // it will update 2 rows so it will return 2
    // Return 1 if the update was successful, otherwise return 0
    echo ($res==2) ? 1 : 0;
  }


  // Handle the cancellation of a booking
  if(isset($_POST['cancel_booking']))
  {
    // Clean the input data from the form
    $frm_data = filteration($_POST);
    
    // SQL query to update booking status to 'cancelled' and refund to 0
    $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
    $values = ['cancelled',0,$frm_data['booking_id']];
    // Execute the update query and return the result (1 if successful, 0 if failed)
    $res = update($query,$values,'sii');

    echo $res;
  }

?>