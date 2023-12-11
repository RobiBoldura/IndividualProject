<?php
session_start();

include("phpscripts/connections.php");
include("phpscripts/functions.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpscripts/vendor/autoload.php'; // Path to Composer autoload

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($conn, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $token = bin2hex(random_bytes(50));

    // Insert user data into the database
    $query = "INSERT into `users` (username, password, email, token, isEmailConfirmed)
              VALUES ('$username', '" . md5($password) . "', '$email', '$token', '0')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Send email verification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'robertboldura@gmail.com'; // Your Gmail username
            $mail->Password = 'ldbs gzll pkcn ufop'; // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            //Recipients
            $mail->setFrom('robertboldura@gmail.com', 'FolkMarket'); // Your Gmail address and your name
            $mail->addAddress($email, $username); // User's email and username
            $mail->addReplyTo('robertboldura@gmail.com', 'FolkMarket'); // Your Gmail address and your name

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "Click the following link to verify your email: <a href='http://localhost/FolkMarket/verified.php?token=$token'>Verify Email</a>";

            $mail->send();
            echo "
                  <h3>You are registered successfully. Please check your email for verification.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>";
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Please enter some valid information!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style_login.css">
</head>

<body>
    <div class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php" class="logo-image"><img src="imagini/logo.png" alt="logo" width="125px"></a>
            </div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="aboutus.php">About</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="login-box">
            <h2>Sign Up</h2>
            <form method="post">
                <label for="username">Username:</label>
                <input class="text-input" type="text" name="username"><br><br>
                <label for="password">Password:</label>
                <input class="text-input" type="password" name="password"><br><br>
                <label for="email">Email:</label>
                <input class="text-input" type="email" name="email"><br><br>
                <input class="login-button" type="submit" value="Signup"><br><br>
                <a href="login.php">Click to Login</a>
            </form>
        </div>
    </div>
</body>

</html>
