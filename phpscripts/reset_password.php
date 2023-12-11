<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style_reset_password.css">
</head>
<body>
    <div class="container">
        <div class="reset-box">
            <h2>Reset Password</h2>
            <form method="post" action="update_password.php">
                <label for="password">New Password:</label>
                <input class="text-input" type="password" name="password" required><br><br>
                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                <input class="reset-button" type="submit" value="Reset Password"><br><br>
            </form>
        </div>
    </div>
</body>
</html>
