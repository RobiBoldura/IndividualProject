<!-- recovery.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="css/style_recovery.css">
</head>
<body>
    <div class="container">
        <div class="recovery-box">
            <h2>Password Recovery</h2>
            <form method="post" action="send_recovery_email.php">
                <label for="email">Email:</label>
                <input class="text-input" type="email" name="email" required><br><br>
                <input class="recovery-button" type="submit" value="Send Recovery Email"><br><br>
                <a href="login.php">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>
