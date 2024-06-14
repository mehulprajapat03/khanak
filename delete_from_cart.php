<?php
include('server.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['product_id'])) {
    // Retrieve user_id and product_id from POST data
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];

    // Prepare and execute the SQL query to delete the item from the cart table
    $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Item deleted successfully
        header("Location: cart.php?user_id=$user_id");
        exit();
    } else {
        // Error handling
        echo "Error deleting item from cart: " . mysqli_error($conn);
    }
} else {
    // Handle case where user_id or product_id is not provided
    echo "Invalid request.";
}
?>
