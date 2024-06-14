<?php

include('server.php');


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
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo.png">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <img src="img/logo.png" class="logo" alt="n">
                <a href="<?php echo $index_link; ?>" class="navbar-brand">
                    <h1 class="text-primary display-6" style="font-family: droid serif; padding-top:7%">KHANAK</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="<?php echo $index_link; ?>" class="nav-item nav-link ">Home</a>
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


    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">User Details</h1>
    </div>
    <!-- Single Page Header End -->


    <!-- User Profile Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <form action="#">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="col-lg-12">
                            <a href="#">
                                <img src="img/profile.jpg" class="img-fluid-40 rounded" alt="Image">
                            </a>
                            <a href="index.php"><button type="button" class="w-25 btnlogout form-control border-secondary py-3 bg-white text-primary">Log Out</button></a>
                        </div>
                    </div>

                    
                    <div class="px-2">
                        <div class="row g-4">

                        <?php
                    include('server.php');

                    // Check if the user_id parameter is set in the URL
                    if (isset($_GET['user_id'])) {
                        // Get the user_id from the URL
                        $user_id = $_GET['user_id'];

                        // Fetch user details based on the user_id
                        $select_query = "SELECT u.username, ud.first_name, ud.last_name, ud.mobile, ud.email, ud.gender 
                        FROM users u
                        JOIN users_detail ud ON u.user_id = ud.user_id
                        WHERE u.user_id = $user_id";
                        $result_query = mysqli_query($conn, $select_query);

                        // Check if the query returned any rows
                        if (mysqli_num_rows($result_query) > 0) {
                            // Fetch user details
                            $row = mysqli_fetch_assoc($result_query);
                            $username = $row['username'];
                            $first_name = $row['first_name'];
                            $last_name = $row['last_name'];
                            $mobile = $row['mobile'];
                            $email = $row['email'];
                            $gender = $row['gender']; // Assuming this field exists in your database
                        } else {
                            // Handle case where user is not found
                            echo "User not found";
                        }
                    } else {
                        // Handle case where user_id parameter is not set in the URL
                        echo "User ID not provided";
                    }
                    echo"
                <div class='col-6'>
                    <div class='row bg-light align-items-center text-center justify-content-center py-2'>
                        <div class='col-6'>
                            <p class='mb-0'>USERNAME</p>
                        </div>
                        <div class='col-6'>
                            <p class='mb-0'>$username</p>
                        </div>
                    </div>
                    <div class='row text-center align-items-center justify-content-center py-2'>
                        <div class='col-6'>
                            <p class='mb-0'>NAME</p>
                        </div>
                        <div class='col-6'>
                            <p class='mb-0'>$first_name $last_name</p>
                        </div>
                    </div>
                    <div class='row bg-light text-center align-items-center justify-content-center py-2'>
                        <div class='col-6'>
                            <p class='mb-0'>EMAIL</p>
                        </div>
                        <div class='col-6'>
                            <p class='mb-0'>$email</p>
                        </div>
                    </div>
                    <div class='row text-center align-items-center justify-content-center py-2'>
                        <div class='col-6'>
                            <p class='mb-0'>PHONE NO.</p>
                        </div>
                        <div class='col-6'>
                            <p class='mb-0'>$mobile</p>
                        </div>
                    </div>
                    <div class='row bg-light text-center align-items-center justify-content-center py-2'>
                        <div class='col-6'>
                            <p class='mb-0'>GENDER</p>
                        </div>
                        <div class='col-6'>
                            <p class='mb-0'>$gender</p>
                        </div>
                    </div>
                </div>"; ?>
                        </div>
                    </div>
                </div>
                <a href="orders.php?user_id=<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>"><button type="button" class="w-15 btnorder form-control border-secondary py-3 bg-white text-primary">Orders</button></a>
            </form>
        </div>
    </div>
    <!-- User Profile End -->
    


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
                    <span class="text-light"><a href="#" style="align-items: center;"><i
                                class="fas fa-copyright text-light me-2"></i>KHANAK</a>, All right reserved.</span>
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>