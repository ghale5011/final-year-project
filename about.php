<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Include Additional Links -->
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - ABOUT</title>

    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Header -->
    <?php require('inc/header.php'); ?>

    <!-- Page Title Section -->
    <div class="my-4 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Deserunt, ea sunt neque totam <br>repudiandae necessitatibus
            eligendi officia pariatur nesciunt. Id?
        </p>
    </div>

    <!-- About Section -->
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <!-- About Text -->
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Lorem ipsum dolor sit.</h3>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Minus et ut eius assumenda. Vel, doloribus quibusdam!
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Minus et ut eius assumenda. Vel, doloribus quibusdam!
                </p>
            </div>
            <!-- About Image -->
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/about.png" class="w-100">
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="container">
        <div class="row">
            <!-- Stat Box -->
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/room.png" width="70px">
                    <h4 class="mt-3">100+ ROOMS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customers.png" width="70px">
                    <h4 class="mt-3">200+ CUSTOMERS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/reviews.png" width="70px">
                    <h4 class="mt-3">200+ REVIEWS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staffs.png" width="70px">
                    <h4 class="mt-3">50+ STAFFS</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Team Section -->
    <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>
    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
            <?php 
                $about_r = selectAll('team_details');
                $path=ABOUT_IMG_PATH;
                while($row = mysqli_fetch_assoc($about_r)){
                    echo<<<data
                        <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                            <img src="$path$row[picture]" class="w-100">
                            <h5 class="mt-2">$row[name]</h5>
                        </div>
                    data;
                }
            ?>
            
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: { slidesPerView: 1 },
                640: { slidesPerView: 1 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 3 },
            },
        });
    </script>
</body>

</html>
