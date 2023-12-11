<?php
session_start();

include("phpscripts/connections.php");
include("phpscripts/functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    {
        $username = stripslashes($_POST['username']);
        $username = mysqli_real_escape_string($conn, $username);
        $password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($conn, $password);
    
        $query = "SELECT * FROM `users` WHERE username='$username' AND password='" . md5($password) . "' AND isEmailConfirmed='1'";
        $result = mysqli_query($conn ,$query);
        $rows = mysqli_num_rows($result);
    
        if ($rows == 1) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = mysqli_fetch_assoc($result)['id'];
            header("Location:index.php");
        } else {
            echo "
                  <h3>Login failed. Please check your username, password, or verify your email.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                    <li><a href="home.php">Shop</a></li>
                    <li><a href="aboutus.php">About</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="post">
                <label for="username">Username:</label>  <!-- Schimbat de la user_name la username -->
                <input class="text-input" type="text" name="username"><br><br>  <!-- Schimbat de la user_name la username -->
                <label for="password">Password:</label>
                <input class="text-input" type="password" name="password"><br><br>
                <input class="login-button" type="submit" value="Login"><br><br>
                  
                <a href="signup.php">Click to Signup</a>
            </form>
        </div>
    </div>
</body>
</html>
