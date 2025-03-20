    let feature_s_form = document.getElementById('feature_s_form');
    let facility_s_form = document.getElementById('facility_s_form');

    // Event listener for feature form submission
    feature_s_form.addEventListener('submit', function(e) {
        e.preventDefault();
        add_feature();
    });

    // Function to add a new feature via AJAX
    function add_feature() {
        let data = new FormData();
        data.append('name', feature_s_form.elements['feature_name'].value);
        data.append('add_feature', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function() {
            var myModal = document.getElementById('feature-s');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == 1) {
                alert('success', 'New feature added!');
                feature_s_form.elements['feature_name'].value = '';
                get_features();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
    }

    // Function to fetch and display features
    function get_features() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            document.getElementById('features_data').innerHTML = this.responseText;
        }
        xhr.send('get_features');
    }

    // Function to remove a feature
    function rem_feature(val) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Feature removed!');
                get_features();
            } else if (this.responseText == 'room_added') {
                alert('error', 'Feature is added in room!');
            } else {
                alert('error', 'Server down!');
            }
        }

        xhr.send('rem_feature=' + val);
    }

    // Event listener for facility form submission
    facility_s_form.addEventListener('submit', function(e) {
        e.preventDefault();
        add_facility();
    });

    // Function to add a new facility via AJAX
    function add_facility() {
        let data = new FormData();
        data.append('name', facility_s_form.elements['facility_name'].value);
        data.append('icon', facility_s_form.elements['facility_icon'].files[0]);
        data.append('desc', facility_s_form.elements['facility_desc'].value);
        data.append('add_facility', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function() {
            var myModal = document.getElementById('facility-s');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == 'inv_img') {
                alert('error', 'Only SVG images are allowed!');
            } else if (this.responseText == 'inv_size') {
                alert('error', 'Image size should be less than 2MB!');
            } else if (this.responseText == 'upd_failed') {
                alert('error', 'Image upload failed. Server Down!');
            } else {
                alert('success', 'New facility added!');
                facility_s_form.reset();
                get_facilities();
            }
        }
        xhr.send(data);
    }

    // Function to fetch and display facilities
    function get_facilities() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            document.getElementById('facilities_data').innerHTML = this.responseText;
        }
        xhr.send('get_facilities');
    }

    // Function to remove a facility
    function rem_facility(val) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Facility removed!');
                get_facilities();
            } else if (this.responseText == 'room_added') {
                alert('error', 'Facility is added in room!');
            } else {
                alert('error', 'Server down!');
            }
        }

        xhr.send('rem_facility=' + val);
    }

    // Load features and facilities when the page loads
    window.onload = function() {
        get_features();
        get_facilities();
    }