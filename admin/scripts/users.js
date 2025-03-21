// Function to fetch all users from the database and display them in the table
function get_users() {
    let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open("POST", "ajax/users.php", true); // Initialize a POST request to 'ajax/users.php'
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set request header

    // Define what happens when the response is received
    xhr.onload = function() {
        // Update the table body with the response data
        document.getElementById('users-data').innerHTML = this.responseText;
    }

    // Send the request with the action 'get_users'
    xhr.send('get_users');
}

// Function to toggle the status of a user (e.g., active/inactive)
function toggle_status(id, val) {
    let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open("POST", "ajax/users.php", true); // Initialize a POST request to 'ajax/users.php'
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set request header

    // Define what happens when the response is received
    xhr.onload = function() {
        if (this.responseText == 1) { // If the operation is successful
            alert('success', 'Status toggled!'); // Show success message
            get_users(); // Refresh the user list
        } else {
            alert('success', 'Server Down!'); // Show error message if the server fails
        }
    }
    // Send the request with the user ID and new status value
    xhr.send('toggle_status=' + id + '&value=' + val);
}

// Function to remove a user from the database
function remove_user(user_id) {
    // Confirm with the admin before proceeding
    if (confirm("Are you sure, you want to remove this user?")) {
        let data = new FormData(); // Create a new FormData object
        data.append('user_id', user_id); // Append the user ID to the form data
        data.append('remove_user', ''); // Append the action 'remove_user'

        let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
        xhr.open("POST", "ajax/users.php", true); // Initialize a POST request to 'ajax/users.php'

        // Define what happens when the response is received
        xhr.onload = function() {
            if (this.responseText == 1) { // If the operation is successful
                alert('success', 'User Removed!'); // Show success message
                get_users(); // Refresh the user list
            } else {
                alert('error', 'User removal failed!'); // Show error message if the operation fails
            }
        }

        // Send the request with the form data
        xhr.send(data);
    }
}

// Function to search for users by name
function search_user(username) {
    let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open("POST", "ajax/users.php", true); // Initialize a POST request to 'ajax/users.php'
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set request header

    // Define what happens when the response is received
    xhr.onload = function() {
        // Update the table body with the filtered user data
        document.getElementById('users-data').innerHTML = this.responseText;
    }

    // Send the request with the search query
    xhr.send('search_user&name=' + username);
}

// Fetch all users when the page loads
window.onload = function() {
    get_users(); // Call the get_users function to populate the table
}