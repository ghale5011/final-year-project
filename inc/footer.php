<!-- Footer Section -->
<div class="container-fluid bg-white mt-5">
    <div class="row">
        <!-- Hotel Description -->
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2"><?php echo $settings_r['site_title'] ?></h3>
            <p>
                <?php echo $settings_r['site_about'] ?>
            </p>
        </div>

        <!-- Links Section -->
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a> <br>
            <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a> <br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a> <br>
            <a href="contat.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact us</a> <br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
        </div>

        <!-- Social Media Section -->
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
            <?php
                // Check if Twitter link exists and display it
                if($contact_r['tw']!=''){
                    echo<<<data
                        <a href="$contact_r[tw]" class="d-inline-block text-dark text-decoration-none mb-2">
                            <i class="bi bi-twitter me-1"></i> Twitter
                        </a><br>
                        data;
                    }
            ?>
            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook me-1"></i> Facebook
            </a><br>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark text-decoration-none">
                <i class="bi bi-instagram me-1"></i> Instagram
            </a>
        </div>
    </div>
</div>

<!-- Footer Credit -->
<h6 class="text-center bg-dark text-white p-3 mb-0">
    Designed and Developed by Suman Ghale
</h6>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    // Function to display alerts
    function alert(type,msg,position='body')
    {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
        <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
            <strong class="me-3">${msg}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;

        if(position=='body'){
        document.body.append(element);
        element.classList.add('custom-alert');
        }
        else{
        document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 3000);
    }

    // Function to remove alerts
    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }

    // Function to set the active class on the navigation bar links
    function setActive()
    {
        // Get the navbar element by its ID
        let navbar = document.getElementById('nav-bar');
        
        // Get all the anchor (<a>) tags within the navbar
        let a_tags = navbar.getElementsByTagName('a');

        // Loop through each anchor tag
        for(i=0; i<a_tags.length; i++)
        {
            // Extract the file name from the href attribute
            // split('/').pop() gets the last part of the URL after the last '/'
            let file = a_tags[i].href.split('/').pop();
            
            // Extract the file name without the extension
            // split('.')[0] gets the part before the first '.'
            let file_name = file.split('.')[0];

            // Check if the current document's URL contains the file name
            if(document.location.href.indexOf(file_name) >= 0){
                // If it does, add the 'active' class to the anchor tag
                a_tags[i].classList.add('active');
            }
        }
    }

    // Register Form Submission Handling
    let register_form = document.getElementById('register-form');

    register_form.addEventListener('submit', (e)=>{
        e.preventDefault();

        let data = new FormData();
        // Append form data to the FormData object
        data.append('name',register_form.elements['name'].value);
        data.append('email',register_form.elements['email'].value);
        data.append('phonenum',register_form.elements['phonenum'].value);
        data.append('address',register_form.elements['address'].value);
        data.append('pincode',register_form.elements['pincode'].value);
        data.append('dob',register_form.elements['dob'].value);
        data.append('pass',register_form.elements['pass'].value);
        data.append('cpass',register_form.elements['cpass'].value);
        data.append('profile',register_form.elements['profile'].files[0]);
        data.append('register','');

        var myModal = document.getElementById('registerModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        // Send form data via AJAX
        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/login_register.php",true);

        xhr.onload = function(){
            // Handle server response
            if(this.responseText == 'pass_mismatch'){
                alert('error',"Password Mismatch!");
            }
            else if(this.responseText == 'email_already'){
                alert('error',"Email is already registered!");
            }
            else if(this.responseText == 'phone_already'){
                alert('error',"Phone number is already registered!");
            }
            else if(this.responseText == 'inv_img'){
                alert('error',"Only JPG, WEBP & PNG images are allowed!");
            }
            else if(this.responseText == 'upd_failed'){
                alert('error',"Image upload failed!");
            }
            else if(this.responseText == 'mail_failed'){
                alert('error',"Cannot send confirmation email! Server down!");
            }
            else if(this.responseText == 'ins_failed'){
                alert('error',"Registration failed! Server down!");
            }
            else{
                alert('success',"Registration successful. Confirmation link sent to email!");
                register_form.reset();
            }
        }
        xhr.send(data);
    });

    // Login Form Submission Handling
    let login_form = document.getElementById('login-form');

    login_form.addEventListener('submit', (e)=>{
        e.preventDefault();

        let data = new FormData();

        data.append('email_mob',login_form.elements['email_mob'].value);
        data.append('pass',login_form.elements['pass'].value);
        data.append('login','');

        var myModal = document.getElementById('loginModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/login_register.php",true);

        xhr.onload = function(){
            if(this.responseText == 'inv_email_mob'){
                alert('error',"Invalid Email or Mobile Number!");
            }
            else if(this.responseText == 'not_verified'){
                alert('error',"Email is not verified!");
            }
            else if(this.responseText == 'inactive'){
                alert('error',"Account Suspended! Please contact Admin.");
            }
            else if(this.responseText == 'invalid_pass'){
                alert('error',"Incorrect Password!");
            }
            else{
                let fileurl = window.location.href.split('/').pop().split('?').shift();
                if(fileurl == 'room_details.php'){
                window.location = window.location.href;
                }
                else{
                window.location = window.location.pathname;
                }
            }
        }
        xhr.send(data);
    });

    // Forgot Password Form Submission Handling
    let forgot_form = document.getElementById('forgot-form');

    forgot_form.addEventListener('submit', (e)=>{
        e.preventDefault();

        let data = new FormData();

        data.append('email',forgot_form.elements['email'].value);
        data.append('forgot_pass','');

        var myModal = document.getElementById('forgotModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/login_register.php",true);

        xhr.onload = function(){
            if(this.responseText == 'inv_email'){
            alert('error',"Invalid Email !");
            }
            else if(this.responseText == 'not_verified'){
            alert('error',"Email is not verified! Please contact Admin");
            }
            else if(this.responseText == 'inactive'){
            alert('error',"Account Suspended! Please contact Admin.");
            }
            else if(this.responseText == 'mail_failed'){
            alert('error',"Cannot send email. Server Down!");
            }
            else if(this.responseText == 'upd_failed'){
            alert('error',"Account recovery failed. Server Down!");
            }
            else{
            alert('success',"Reset link sent to email!");
            forgot_form.reset();
            }
        }

    xhr.send(data);
    });

    // Function to check if the user is logged in before booking a room
    function checkLoginToBook(status,room_id){
    if(status){
      window.location.href='confirm_booking.php?id='+room_id;
    }
    else{
      alert('error','Please login to book room!');
    }
  }

    // Call the setActive function to apply the active class to the current navigation link    
    setActive();
    
</script>
