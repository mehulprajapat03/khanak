<?php
// Include the server.php file for database connection
include('server.php');

//Check if the user_id and product_id are provided in the POST request 
if (isset($_POST['user_id'], $_POST['product_id'], $_POST['quantity'])) {
    // Sanitize input values
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // Debugging output

    // Update the quantity in the cart table
    $update_query = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
    $result = mysqli_query($conn, $update_query);

    // Check if the query was successful
    if ($result) {
        // Quantity updated successfully
        // Redirect back to the cart page
        header("Location: cart.php?user_id=$user_id");
        exit();
    } else {
        // Error occurred while updating quantity
        echo "Error updating quantity: " . mysqli_error($conn);
    }
} else {
    // If user_id, product_id, or quantity are not provided in the POST request
    echo "Invalid request";
}
?>
