<?php
session_start();

include("connections.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $password = $_POST['password'];
    $token = $_POST['token'];

    // Verifică dacă tokenul există și nu a expirat
    $query = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expires_at > NOW()";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $token);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Actualizează parola și elimină tokenul
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE reset_token = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);

            if ($updateStmt) {
                mysqli_stmt_bind_param($updateStmt, 'ss', $hashed_password, $token);
                mysqli_stmt_execute($updateStmt);

                header("Location: login.php");
                die;
            } else {
                echo "Error preparing update query: " . mysqli_error($con);
            }
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Error preparing query: " . mysqli_error($con);
    }
}
?>
