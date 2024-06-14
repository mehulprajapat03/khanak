<?php
include('server.php');

// Initialize user_id variable
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Initialize cart count variable
$cart_count = 0;

// Check if user_id is set
if ($user_id !== null) {
    // Append user_id to the links
    $index_link = "index.php?user_id=$user_id";
    $shop_link = "shop.php?user_id=$user_id";
    $contact_link = "contact.php?user_id=$user_id";
    $user_profile_link = "user_profile.php?user_id=$user_id";
    $cart_link = "cart.php?user_id=$user_id";

    // Query to fetch the count of items in the cart for the given user
    $cart_count_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = '$user_id'";
    $cart_count_result = mysqli_query($conn, $cart_count_query);
    $cart_count_row = mysqli_fetch_assoc($cart_count_result);
    $cart_count = $cart_count_row['cart_count'];
} else {
    // If user_id is not present, link to login.php without user_id
    $shop_link = "login.php";
    $index_link = "login.php";
    $contact_link = "login.php";
    $user_profile_link = "login.php";
    $cart_link = "login.php";
    $cart_count_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = '$user_id'";
    $cart_count_result = mysqli_query($conn, $cart_count_query);
    $cart_count_row = mysqli_fetch_assoc($cart_count_result);
    $cart_count = $cart_count_row['cart_count'];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $postcode = $_POST['postcode'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $notes = $_POST['notes'];
    // Retrieve user_id from POST data
    $user_id = $_POST['user_id'];

    // Insert data into the billing_detail table
    $insert_billing_query = "INSERT INTO billing_detail (first_name, last_name, address, city, country, postcode, mobile, email, notes, user_id) VALUES ('$first_name', '$last_name', '$address', '$city', '$country', '$postcode', '$mobile', '$email', '$notes', '$user_id')";
    
    if (mysqli_query($conn, $insert_billing_query)) {
        // Copy cart data to the orders table
        $copy_cart_query = "INSERT INTO orders (user_id, product_id, product_name, price, quantity, total) SELECT user_id, product_id, product_name, price, quantity, total FROM cart WHERE user_id = $user_id";
        if (mysqli_query($conn, $copy_cart_query)) {
            // Delete cart data for the user after copying to orders table
            $delete_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
            if (mysqli_query($conn, $delete_cart_query)) {
                // Display an alert message
                echo '<script>alert("Your order has been successfully placed");</script>';
                // Redirect to a success page or do something else upon successful deletion
                echo '<script>window.location.href = "orders.php?user_id='.$user_id.'";</script>';
                exit();
            } else {
                echo "Error deleting cart data: " . mysqli_error($conn);
            }
        } else {
            echo "Error copying cart data: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting billing details: " . mysqli_error($conn);
    }
}

// Initialize subtotal and shipping variables
$subtotal = 0;
$shipping = 0;

// Query to calculate subtotal from cart items for the given user
$select_query = "SELECT SUM(total) AS subtotal FROM cart WHERE user_id = $user_id";
$result_query = mysqli_query($conn, $select_query);
$row = mysqli_fetch_assoc($result_query);
if ($row['subtotal'] !== null) {
    $subtotal = $row['subtotal'];
}

// Check if there are records in the cart
$check_shipping_query = "SELECT * FROM cart";
$result_shipping_query = mysqli_query($conn, $check_shipping_query);
if (mysqli_num_rows($result_shipping_query) > 0) {
    // Set shipping cost to 100 if records exist
    $shipping = 100;
} else {
    // Set shipping cost to 0 if no records found
    $shipping = 0;
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
                    <img src="img/logo.png" class = "logo" alt="n">
                    <a href="<?php echo $index_link; ?>" class="navbar-brand"><h1 class="text-primary display-6" style=" font-family: droid serif;padding-top:7%;">KHANAK</h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
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
            <h1 class="text-center text-white display-6">Checkout</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Billing details</h1>
                <form name="checkoutForm" action="" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="user_id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">
                    <div class="row g-5">
                        <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">First Name<sup>*</sup></label>
                                    <input type="text" class="form-control" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Last Name<sup>*</sup></label>
                                    <input type="text" class="form-control" name="last_name">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Address <sup>*</sup></label>
                            <input type="text" class="form-control" placeholder="House Number Street Name" name="address">
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Town/City<sup>*</sup></label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Country<sup>*</sup></label>
                            <input type="text" class="form-control" name="country">
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Postcode/Zip<sup>*</sup></label>
                            <input type="text" class="form-control" name="postcode">
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Mobile<sup>*</sup></label>
                            <input type="tel" class="form-control" name="mobile">
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Email Address<sup>*</sup></label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <hr>
                        <div class="form-item">
                            <textarea name="notes" class="form-control" spellcheck="false" cols="30" rows="11" placeholder="Order Notes (Optional)"></textarea>
                        </div>
                    </div>
                        <div class="col-md-12 col-lg-6 col-xl-5">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Products</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                            if(isset($_GET['user_id'])){
                                $user_id = $_GET['user_id'];
                            }
                            $select_query = "SELECT cart.*, product_detail.p_img FROM cart INNER JOIN product_detail ON cart.product_id = product_detail.product_id WHERE cart.user_id = $user_id";
                            $result_query = mysqli_query($conn, $select_query);
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $product_name = $row['product_name'];
                                $product_id =$row['product_id']; 
                                $quantity = $row['quantity'];
                                $price = $row['price'];
                                $total = $row['total'];
                                $p_img = $row['p_img'];
                                echo "
                                        <tr>
                                            <th scope='row'>
                                                <div class='d-flex align-items-center mt-2'>
                                                    <img src='$p_img' class='img-fluid rounded-circle' style='width: 90px; height: 90px;' alt=''>
                                                </div>
                                            </th>
                                            <td class='py-5'>$product_name</td>
                                            <td class='py-5'>₹$price</td>
                                            <td class='py-5'>$quantity</td>
                                            <td class='py-5'>₹ $total</td>
                                        </tr>";}?>

                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Subtotal</p>
                                            </td>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark">₹<?php echo $subtotal; ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Shipping</p>
                                            </td>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td colspan="2" class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <label class="form-check-label" for="Shipping-2">Flat rate: ₹<?php echo $shipping ; ?></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                                            </td>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark">₹ <?php echo $subtotal + $shipping; ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->


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
                        <span class="text-light"><a href="#" style="align-items: center;"><i class="fas fa-copyright text-light me-2"></i>KHANAK</a>, All right reserved.</span>
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
    <script>
        function validateForm() {
        var firstName = document.forms["checkoutForm"]["first_name"].value;
        var lastName = document.forms["checkoutForm"]["last_name"].value;
        var address = document.forms["checkoutForm"]["address"].value;
        var city = document.forms["checkoutForm"]["city"].value;
        var country = document.forms["checkoutForm"]["country"].value;
        var postcode = document.forms["checkoutForm"]["postcode"].value;
        var mobile = document.forms["checkoutForm"]["mobile"].value;
        var email = document.forms["checkoutForm"]["email"].value;

        // Regular expressions for email and mobile number validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var mobileRegex = /^[0-9]{10}$/;

        if (firstName == "" || lastName == "" || address == "" || city == "" || country == "" || postcode == "" || mobile == "" || email == "") {
            alert("All fields must be filled out");
            return false;
        }

        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address");
            return false;
        }

        if (!mobileRegex.test(mobile)) {
            alert("Please enter a valid 10-digit mobile number");
            return false;
        }
    }
</script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>