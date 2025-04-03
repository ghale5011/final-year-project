// Function to get bookings based on a search term (default is an empty string)
function get_bookings(search='') {
    let xhr = new XMLHttpRequest();  // Create a new XMLHttpRequest object
    xhr.open("POST","ajax/new_bookings.php",true);  // Set up the POST request to 'new_bookings.php'
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  // Set the request header for form data
  
    // When the response is received, update the table with the returned data
    xhr.onload = function() {
      document.getElementById('table-data').innerHTML = this.responseText;  // Update the 'table-data' element with the response
    }
  
    // Send the request with the search data
    xhr.send('get_bookings&search=' + search);
  }
  
  // Get the form element for assigning rooms
  let assign_room_form = document.getElementById('assign_room_form');


  // Function to assign a room to a booking when the "Assign Room" button is clicked
  function assign_room(id) {
    assign_room_form.elements['booking_id'].value = id;  // Set the booking ID in the form
  }
  
  // Event listener for form submission to assign the room
  assign_room_form.addEventListener('submit', function(e) {
    e.preventDefault();  // Prevent the default form submission behavior
  
    let data = new FormData();  // Create a new FormData object
    data.append('room_no', assign_room_form.elements['room_no'].value);  // Append room number to the data
    data.append('booking_id', assign_room_form.elements['booking_id'].value);  // Append booking ID to the data
    data.append('assign_room', '');  // Indicate that this is an "assign room" action
  
    let xhr = new XMLHttpRequest();  // Create a new XMLHttpRequest object
    xhr.open("POST", "ajax/new_bookings.php", true);  // Set up the POST request to 'new_bookings.php'
  
    // When the response is received, hide the modal and provide feedback
    xhr.onload = function() {
      var myModal = document.getElementById('assign-room');  // Get the modal element
      var modal = bootstrap.Modal.getInstance(myModal);  // Get the bootstrap modal instance
      modal.hide();  // Hide the modal after submission
  
      // Check if the room was assigned successfully
      if (this.responseText == 1) {
        alert('success', 'Room Number Alloted! Booking Finalized!');
        assign_room_form.reset();  // Reset the form fields
        get_bookings();  // Refresh the bookings list
      }
      else {
        alert('error', 'Server Down!');  // Show error alert if something went wrong
      }
    }
  
    // Send the form data to the server
    xhr.send(data);
  });


  
  // Function to cancel a booking
  function cancel_booking(id) {
    if (confirm("Are you sure, you want to cancel this booking?")) {  // Confirm the cancellation
      let data = new FormData();  // Create a new FormData object
      data.append('booking_id', id);  // Append the booking ID to the data
      data.append('cancel_booking', '');  // Indicate that this is a "cancel booking" action
  
      let xhr = new XMLHttpRequest();  // Create a new XMLHttpRequest object
      xhr.open("POST", "ajax/new_bookings.php", true);  // Set up the POST request to 'new_bookings.php'
  
      // When the response is received, show feedback to the user
      xhr.onload = function() {
        if (this.responseText == 1) {
          alert('success', 'Booking Cancelled!');  // Show success alert
          get_bookings();  // Refresh the bookings list
        }
        else {
          alert('error', 'Server Down!');  // Show error alert if something went wrong
        }
      }
  
      // Send the form data to the server
      xhr.send(data);
    }
  }
  
  // When the page is loaded, fetch the bookings data
  window.onload = function() {
    get_bookings();  // Call the get_bookings function to load the data
  }
  