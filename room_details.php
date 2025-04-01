<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Additional Links -->
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - ROOMS DETAILS</title>

</head>

<body class="bg-light">

    <!-- Header -->
    <?php require('inc/header.php'); ?>

    <?php 
        if(!isset($_GET['id'])){
            redirect('rooms.php');
        }

        // Sanitize and filter the room ID from the URL
        $data = filteration($_GET);

        // Fetch the room data from the database based on the sanitized and filtered room ID
        $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

        // Check if the room data exists and is active in the database
        if(mysqli_num_rows($room_res)==0){
            redirect('rooms.php');
        }

        // Fetch room data as an associative array
        $room_data = mysqli_fetch_assoc($room_res);
    ?>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Breadcrumb and Room Title -->
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $room_data['name'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                </div>
            </div>

            <!-- Room Image Carousel -->
            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php 
                            // Default room image
                            $room_img = ROOMS_IMG_PATH."thumbnail.jpg";
                            // Fetch room images from the database
                            $img_q = mysqli_query($con, "SELECT * FROM `room_images`
                                WHERE `room_id` = '$room_data[id]'");
                            if (mysqli_num_rows($img_q)>0)
                            {
                                $active_class = 'active';

                                while($img_res = mysqli_fetch_assoc($img_q))
                                {
                                    echo"
                                        <div class='carousel-item $active_class'>
                                            <img src='".ROOMS_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                                        </div>
                                    ";
                                    $active_class = '';
                                }
                            }
                            else{
                                echo"<div class='carousel-item active'>
                                <img src='$room_img' class='d-block w-100'>
                            </div>";
                            }
                        ?>
                    </div>
                    <!-- Carousel Navigation Buttons -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <!-- Room Details Section -->
            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php 
                            // Display room price
                            echo<<<price
                                <h4>Rs $room_data[price] per night</h6>
                            price;
                            // Display room rating
                            echo <<<rating
                                <div class="mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                            rating;

                            // Fetch and display room features
                            $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                                INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
                                WHERE rfea.room_id = '$room_data[id]'");

                            $features_data = "";
                            while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                                $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    $fea_row[name]
                                </span>";
                            }

                            echo <<<features
                                <div class="my-3">
                                    <h6 class="mb-1">Features</h6>
                                    $features_data
                                </div>
                            features;

                            // Fetch and display room facilities
                            $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
                                INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
                                WHERE rfac.room_id = '$room_data[id]'");

                            $facilities_data = "";
                            while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                                $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    $fac_row[name]
                                </span>";
                            }

                            echo <<<facilities
                                <div class="my-3">
                                    <h6 class="mb-1">Facilities</h6>
                                    $facilities_data
                                </div>
                            facilities;

                            // Display guest capacity
                            echo <<<guests
                                <div class="mb-3">
                                    <h6 class="mb-1">Guests</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        $room_data[adult] Adults
                                    </span>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        $room_data[children]Children
                                    </span>
                                </div>
                            guests;

                            // Display room area
                            echo<<<area
                                <div class="my-3">
                                    <h6 class="mb-1">Area</h6>
                                    <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                        $room_data[area] sq. ft.
                                    </span>
                                </div>
                            area;

                            $book_btn = "";
                
                            if(!$settings_r['shutdown']){
                                $login=0;
                                if(isset($_SESSION['login']) && $_SESSION['login']==true){
                                    $login=1;
                                }
                                echo<<<book
                                    <button onclick='checkLoginToBook($login,$room_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</button>
                                book;
                            }                            
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Room Description and Reviews Section -->
            <div class="col-12 mt-4 px-4">
                <!-- Room Description -->
                <div class="mb-5">
                    <h5>Description</h5>
                    <p>
                        <?php echo $room_data['description'] ?>
                    </p>
                </div>
                <!-- Reviews and Ratings -->
                <div>
                    <h5 class="mb-3">Review & Rating</h5>
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <img src="images/features/profile.svg" width="30px" alt="User Profile">
                            <h6 class="m-0 ms-2">Random user1</h6>
                        </div>
                        <p>
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                            Saepe, ea sit quod praesentium ducimus modi dolor provident 
                            reiciendis ullam quisquam.
                        </p>
                        <div class="rating">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

</body>

</html>