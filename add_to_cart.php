<?php
session_start();

include('server.php');

if (isset($_GET['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_GET['user_id'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Check if user_id is not equal to 0
    if ($user_id != 0) {
        $check_query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $check_result = mysqli_query($conn, $check_query);

        if(mysqli_num_rows($check_result) > 0) {
            $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
            if(mysqli_query($conn, $update_query)) {
                $_SESSION['success_message'] = "Item quantity updated in the cart successfully.";
                header("Location: shop.php?user_id=$user_id");
                exit();
            } else {
                echo "Error updating quantity: " . mysqli_error($conn);
            }
        } else {
            $select_query = "SELECT * FROM product_detail WHERE product_id = '$product_id'";
            $result_query = mysqli_query($conn, $select_query);

            if(mysqli_num_rows($result_query) > 0) {
                $row = mysqli_fetch_assoc($result_query);
                $product_name = $row['product_name'];
                $price = $row['price'];

                $insert_query = "INSERT INTO cart (user_id, product_id, product_name, price, quantity) VALUES ('$user_id', '$product_id', '$product_name', '$price', 1)";
                if(mysqli_query($conn, $insert_query)) {
                    $_SESSION['success_message'] = "Item added to cart successfully.";
                    header("Location: shop.php?user_id=$user_id");
                    exit();
                } else {
                    echo "Error inserting product into cart: " . mysqli_error($conn);
                }
            } else {
                echo "Product not found.";
            }
        }
    } else {
        echo "User ID cannot be 0.";
    }
} else {
    echo "User ID or product ID not provided.";
}
?>
