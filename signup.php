    <?php
    // Establish database connection
    include('server.php');

    // Process form submission
    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

        // Check if passwords match
        if ($_POST['password'] !== $_POST['confirm_password']) {
            echo "<script>alert('Password and Confirm Password do not match. Please try again.');</script>";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $_POST['password'])) {
            echo "<script>alert('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
        } else {
            // Check if username already exists
            $select = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
            if (mysqli_num_rows($select) > 0) {
                echo "<script>alert('Username already exists! Please choose a different one.');</script>";
            } else {
                // Insert new user if username is unique
                $insert = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username','$password')");
                if ($insert) {
                    // Fetch the user ID of the newly inserted user
                    $user_id = mysqli_insert_id($conn);
                    // Redirect to user_detail.php with user ID as parameter
                    header("Location: user_detail.php?user_id=$user_id");
                    exit(); // Ensure script execution stops after redirection
                } else {
                    echo "<script>alert('Error registering user. Please try again later.');</script>";
                }
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign up Page</title>
        <link rel="stylesheet" href="css/signupcss.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="img/logo.png">
        <script>
            function togglePassword(fieldId) {
                var passwordField = document.getElementById(fieldId);
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                } else {
                    passwordField.type = "password";
                }
            }
        </script>

    </head>

    <body>

        <div class="container">
            <div class="bglayer">
                <div class="form-box">
                    <a href="index.php">
                        <img class="logo" src="img/logo.png" alt="f">
                    </a>
                    <h1 class="signup"> SIGN UP</h1>
                    <div>
                        <hr class="line">
                    </div>
                    <form method="post" action="signup.php">
                        <div class="input-group">
                            <div class="input-field">
                                <input type="text" name="username" required placeholder="Username">
                            </div>
                            <div class="input-field">
                                <input type="password" name="password" id="password" required placeholder="Password">
                                <span class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i> <!-- Font Awesome icon for showing password -->
                                </span>
                            </div>
                            <div class="input-field">
                                <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm Password">
                                <span class="toggle-password" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i> <!-- Font Awesome icon for showing password -->
                                </span>
                            </div>

                        </div>
                        <div>
                            <button class="button" type="submit" name="submit">Sign up</button>
                        </div>
                    </form>


                    <a class="loginpage" href="login.php">Already have an account?</a>
                </div>
            </div>
        </div>


    </body>

    </html>