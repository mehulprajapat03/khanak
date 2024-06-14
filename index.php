<html>
<link rel="icon" type="image/x-icon" href="img/logo.png">
</html>

<?php

// Include the server.php file where your database connection is defined
include('server.php');

// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['userid'])) {
    // Redirect the user to the index page
    header("Location: index.php");
    exit(); // Ensure script execution stops after redirection
}

// Check if user ID is present in the URL
if (isset($_GET['userid'])) {
    // Fetch user ID from the URL
    $userid = $_GET['userid'];

    // Query to select user from the database using user ID
    $select = mysqli_query($conn, "SELECT * FROM users WHERE userid='$userid'");

    // Check if a user with the given user ID exists
    if (mysqli_num_rows($select) > 0) {
        // Fetch user data
        $row  = mysqli_fetch_assoc($select);
        // Set session variables
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['username'] = $row['username'];
        // Redirect to the index page
        header("Location: index.php");
        exit(); // Ensure script execution stops after redirection
    } else {
        // Display an error message if user does not exist
        $error = "User not found";
    }
}

if (isset($_GET['user_id'])) {
    // Get the user_id from the URL
    $user_id = $_GET['user_id'];
    // Append user_id to the link
    $index_link = "index.php?user_id=$user_id";
    $shop_link = "shop.php?user_id=$user_id";
    $contact_link = "contact.php?user_id=$user_id";
    $user_profile_link = "user_profile.php?user_id=$user_id";
    $cart_link = "cart.php?user_id=$user_id";

    $cart_count_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = '$user_id'";
    $cart_count_result = mysqli_query($conn, $cart_count_query);
    $cart_count_row = mysqli_fetch_assoc($cart_count_result);
    $cart_count = $cart_count_row['cart_count'];
} else {
    // If user_id is not present, link to shop.php without user_id
    $shop_link = "login.php";
    $index_link = "login.php";
    $contact_link = "contact.php";
    $user_profile_link = "login.php";
    $cart_link = "login.php";

    $cart_count_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = '$user_id'";
    $cart_count_result = mysqli_query($conn, $cart_count_query);
    $cart_count_row = mysqli_fetch_assoc($cart_count_result);
    $cart_count = $cart_count_row['cart_count'];
}


?>



<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>KHANAK</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 
        
        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="img/logo.png">

        <!-- Libraries Stylesheet -->
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <!-- <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"> -->


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    </head>

    <body>
        <!-- Navbar start -->
        <div class="container-fluid fixed-top" style="padding-left: 0;padding-right: 0;">
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <img src="img/logo.png" class = "logo" alt="n">
                    <a href="<?php echo $index_link; ?>" class="navbar-brand"><h1 class="text-primary display-6" style=" font-family: droid serif;padding-top:7%;">KHANAK</h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="<?php echo $index_link; ?>" class="nav-item nav-link active">Home</a>
                            <a href="<?php echo $shop_link; ?>" class="nav-item nav-link">Shop</a>
                            <a href="<?php echo $contact_link; ?>" class="nav-item nav-link">Contact</a>
                        </div>
                        <div class="d-flex m-3 me-0">
                            <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                            <a href="<?php echo $cart_link; ?>" class="position-relative me-4 my-auto">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                                <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cart_count; ?></span>
                            </a>

                            <a href="<?php echo $user_profile_link; ?>" class="my-auto">
                                <i class="fas fa-user fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search Jewellery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="get" action="search.php">
    <input type="hidden" name="user_id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">
    <div class="modal-body d-flex align-items-center">
        <div class="input-group w-75 mx-auto d-flex">
            <input type="search" class="form-control p-3" placeholder="Search" aria-describedby="search-icon-1" name="search">
            <button type="submit"><span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span></button>
        </div>
    </div>
</form>

                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Hero Start -->
        <div class="your-slider-class py-5">
            <div style="width: 1448px; height:600px;"><img src="img/slider/1.png" alt="Image 1"></div>
            <div style="width: 1448px; height:600px;"><img src="img/slider/2.jpg" alt="Image 2"></div>
            <div style="width: 1448px; height:600px;"><img src="img/slider/3.jpg" alt="Image 3"></div>
        </div>
        <!-- Hero End -->


        <div class="container-fluid banner my-5 " style="padding-left: 0; padding-right: 0;">
            <img  style="width: 1477px;" src="img/banner.png">
        </div>

        <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-car-side fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Shipping</h5>
                                <p class="mb-0">All over India</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa-solid fa-indian-rupee-sign fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Affordable</h5>
                                <p class="mb-0">Low and Affordable prices</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>24/7 Support</h5>
                                <p class="mb-0">Support every time fast</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs Section End -->

        
        <!-- Featurs Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="row g-4 justify-content-center">
                    <h1 style="text-align: center;">CATEGORY</h1>
                    <div class="col-md-6 col-lg-4">
                        <a href="shop-cat.php?category='pendant'&user_id=<?php echo $user_id; ?>">
                            <div class="service-item bg-secondary rounded border border-secondary">
                                <img src="img/h-pendant.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-primary text-center p-4 rounded">
                                        <h5 class="text-white" style="margin-top: 28px;">PENDANT</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="shop-cat.php?category='necklace'&user_id=<?php echo $user_id; ?>">
                            <div class="service-item bg-dark rounded border border-dark">
                                <img src="img/h-necklace.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-light text-center p-4 rounded">
                                        <h5 class="text-primary" style="margin-top: 28px;">NECKLACE</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="shop-cat.php?category='earrings'&user_id=<?php echo $user_id; ?>">
                            <div class="service-item bg-primary rounded border border-primary">
                                <img src="img/h-earing.png" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-secondary text-center p-4 rounded">
                                        <h5 class="text-white" style="margin-top: 28px;">EARRING</h5>
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs End -->


        

        <!-- Bestsaler Product Start -->
        <div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h1 class="display-4">Bestsellers</h1>
        </div>
        <div class="row g-4">
            <?php
            $select_query = "Select * from product_detail LIMIT 6";
            $result_query = mysqli_query($conn, $select_query);
            while ($row = mysqli_fetch_assoc($result_query)) {
                $category = $row['category'];
                $product_id = $row['product_id'];
                $product_name = $row['product_name'];
                $price = $row['price'];
                $small_description = $row['small_description'];
                $p_img = $row['p_img'];
                ?>
                <div class="col-lg-6 col-xl-4">
                    <div class="p-4 rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <a href="shop-detail.php?product_id=<?php echo $product_id; ?>&user_id=<?php echo $user_id; ?>" class="h5">
                                    <img src="<?php echo $p_img; ?>" class="img-fluid rounded-circle w-100" alt="">
                                </a>
                            </div>
                            <div class="col-6">
                                <h4><?php echo $product_name; ?></h4>
                                <div class="d-flex my-3"></div>
                                <h4 class="mb-3">₹ <?php echo $price; ?></h4>
                                <form method="post" action="add_to_cart.php?user_id=<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

        <!-- Bestsaler Product End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid #000 ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0" style="font-family: droid serif;">KHANAK</h1>
                                <p class="text-secondary mb-0">TUM KHUBSURAT HO</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Why People Like us!</h4>
                            <p class="mb-4">Blending artistry with affordability, we're celebrated for bringing high-quality, fashionable artificial jewelry to those who love to sparkle without breaking the bank.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Shop Info</h4>
                            <a class="btn-link" href="">About Us</a>
                            <a class="btn-link" href="<?php echo $contact_link; ?>">Contact Us</a>
                            <a class="btn-link" href="">Return Policy</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Account</h4>
                            <a class="btn-link" href="<?php echo $user_profile_link; ?>">My Account</a>
                            <a class="btn-link" href="<?php echo $cart_link; ?>">Shopping Cart</a>
                            <a class="btn-link" href="orders.php?user_id=<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">Order History</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Contact</h4>
                            <p>Address: 12 RG COLONY, JAIPUR, RAJASTHAN</p>
                            <p>Email: khanakjewelley@gmail.com</p>
                            <p>Phone: +0123 4567 8910</p>
                            <p>Payment Accepted</p>
                            <img src="img/payment.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>KHANAK</a>, All right reserved.</span>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Copyright End -->



        
        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSGFpoO+hxgJlwCflT9T9UkJQ+vr8rFhBnQDn6lr1yB4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-7P148G3JZ3sH3L5gO+2773Kz4fa89M8kF4g520iDz6YjUaXZ4HlYT8r6Z4Hxg4Jg" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-p+T4IbHJ6f6sfzLlIxOq84mGjQ5WABRh/1Hs6sLh/Jf7k7dQjR099cz6K4G/kf4w" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://kit.fontawesome.com/ffdb37c66b.js" crossorigin="anonymous"></script>
    <!-- Template Javascript -->
    <script>
    $(document).ready(function () {
        // Handling click event on the user icon
        $('.fa-user').click(function () {
            // Fetch user ID from the URL
            var urlParams = new URLSearchParams(window.location.search);
            var userId = urlParams.get('user_id');
            
            // Redirect to user_detail.php with the user ID if it's available
            if (userId) {
                window.location.href = 'user_profile.php?user_id=' + userId;
            } else {
                // Redirect to login page if user ID is not available
                window.location.href = 'login.php';
            }
        });
    });
</script>


    <script src="js/main.js"></script>
    </body>

</html>