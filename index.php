<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@1,300&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
    $(document).ready(function() {
        $('.menu-icon').click(function() {
            $('.navbar ul').slideToggle('active');
        });
    });
    </script>

</head>

<body class="body">
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="index.php"><img src="imagini/logo.png" alt="logo" width="125px"></a>
                </div>
                <div class="menu-icon">
                    <i class="fa fa-bars"></i>
                </div>
                <nav>
                    <ul>
                        <li><a href="home.php">Shop</a></li>
                        <li><a href="aboutus.php">About Us</a></li>
                        <?php
                        session_start();
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                             if ($_SESSION['id'] == 8) {
                                echo '<li><a href="addproduct.php">Add Products</a></li>';
                            }
                            echo '<li><a href="logout.php">Logout</a></li>';
                            echo '<a href="cart.php"><img src="imagini/cart.png" alt="logo" width="22px" class="cart-icon"></a>';
                           
                           
                        
                        } else {
                            echo '<li><a href="login.php">Login</a></li>';
                        }
                        
                        ?>
                    </ul>
                </nav>
            </div>
            <div class="row">
                <div class="col-2">
                    <h2>Tradiții în fiecare gust și în fiecare obiect, un legământ cu rădăcinile noastre</h2>
                    <p><b>Tradiții: patrimoniul nostru cultural, o moștenire de preț pentru viitor!</b></p>
                    <a href="home.php" class="btn">Explorează acum &#8594;</a>
                </div>
                <div class="col-22">
                    <img src="imagini/home.png" alt="home" width="500px">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
