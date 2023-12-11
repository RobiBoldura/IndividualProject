<?php
session_start();

include("connections.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];

    // Generare token unic
    $resetToken = bin2hex(random_bytes(32));

    // Setare timp expirare token (de exemplu, 1 oră)
    $resetTokenExpiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salvare token și data expirare în baza de date
    $query = "UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $resetToken, $resetTokenExpiresAt, $email);
        mysqli_stmt_execute($stmt);

        // Trimite e-mail de recuperare
        $subject = 'Password Reset';
        $message = "Click pe link pentru a-ți reseta parola: http://localhost/reset_password.php?token=$resetToken";
        $headers = 'From: webmaster@example.com';

        if (mail($email, $subject, $message, $headers)) {
            echo "E-mail de recuperare trimis cu succes!";
        } else {
            echo "Eroare la trimiterea e-mailului.";
        }

        header("Location: ../login.php");
        die;
    } else {
        echo "Eroare la pregătirea interogării: " . mysqli_error($con);
    }
}
?>
