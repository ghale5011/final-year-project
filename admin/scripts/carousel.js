
    let carousel_s_form = document.getElementById('carousel_s_form');
    let carousel_picture_inp = document.getElementById('carousel_picture_inp');



    carousel_s_form.addEventListener('submit', function(e) {
        e.preventDefault();
        add_image();
    });

    // Function to Add New Image to the Server
    function add_image(){
        let data = new FormData();
        data.append('picture', carousel_picture_inp.files[0]);
        data.append('add_image', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/carousel_crud.php", true);
        
        xhr.onload = function() {
            var myModal = document.getElementById('carousel-s');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if  (this.responseText == 'inv_img') {
                alert('error', 'Only JPG, PNG, and WEBP images are allowed!');
            }
            else if (this.responseText == 'inv_size') {
                alert('error', 'Image size should be less than 2MB!');
            }
            else if (this.responseText == 'upd_failed') {
                alert('error', 'Image upload failed. Server Down!');
            }
            else{
                alert('success', 'New Image added successfully!');
                carousel_picture_inp.value = '';
                get_carousel();
            }
        }
        xhr.send(data);
    }

    // Function to Get the Current Carousel Images from the Server
    function get_carousel(){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/carousel_crud.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            document.getElementById('carousel-data').innerHTML = this.responseText;
        }
        xhr.send('get_carousel');
    }

    // Function to Remove a Carousel Image from the Server
    function rem_image(val){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/carousel_crud.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Image removed!');
                get_carousel();
            } else {
                alert('error', 'Server down!');
            }
        }

        xhr.send('rem_image=' + val);
    }
    
    // Function to Edit a Member by ID
    window.onload = function() {
        get_carousel();
    }
