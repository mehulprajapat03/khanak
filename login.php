<?php

include('server.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are set in the $_POST array
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $select = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

        if (mysqli_num_rows($select) > 0) {
            $row  = mysqli_fetch_assoc($select);
            session_start(); // Start the session
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php?user_id=" . $row['user_id']); // Redirect with user_id as parameter
            exit(); // Ensure script execution stops after redirection
        } else {
            // Set message as alert box
            echo "<script>alert('Incorrect username or password');</script>";
        }
    } else {
        // Handle case where username or password is not set
        echo "<script>alert('Username or password not provided');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/logincss.css' />
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <title>Login</title>
</head>

<body>
    <div class="bglayer">
        <div class="image">
            <img class="sideimg" src="img/jewlery.png" alt="">
        </div>
        <div class="bglayer2">
            <a href="index.php">
            <img class="logo" src="img/logo.png" alt="f">
            </a>
            <h2 class="login">LOGIN</h2>
            <hr class="lgunder">
            <form id="loginForm" method="post" action="login.php">
                <div class="form-group">
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <button type="submit">LOGIN</button>
                </div>
            </form>
            <a href="#" id="forgotPassword">Forgot Password?</a>
            <a href="signup.php" id="createAccount">Create New Account</a>            
        </div>
    </div>

</body>

</html>
