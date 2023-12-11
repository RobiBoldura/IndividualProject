<?php
session_start();
include("phpscripts/connections.php");
require 'phpscripts/vendor/autoload.php'; // Path to Composer autoload


if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "UPDATE `users` SET isEmailConfirmed = '1' WHERE token = '$token'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<h3>Email verification successful. You can now <a href='login.php'>login</a>.</h3>";
    } else {
        echo "<h3>Email verification failed. Please contact support.</h3>";
            
    }
} else {
    echo "<h3>Invalid verification link.</h3>";
}
?>