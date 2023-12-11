<?php
session_start();

include("phpscripts/connections.php");

// Verificare dacă utilizatorul este autentificat
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}



function calculateTotalPrice($cart) {
    $totalPrice = 0;

    foreach ($cart as $product) {
        $totalPrice += $product['pret'] * $product['quantity'];
    }

    return $totalPrice;
}
// Procesare plasare comandă
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $price = calculateTotalPrice($_SESSION['cart']); // Funcție pentru a calcula prețul total
    $address = $_POST['address'];
    $user_email = $_SESSION['email'];
    $phone_no = $_POST['phone_no'];

    // Adaugă comanda în tabelul "orders"
    $insertOrderQuery = "INSERT INTO orders (user_id, price, address, user_email, phone_no) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertOrderQuery);
      
    if (isset($_SESSION['totalPrice'])) {
        $totalPrice = $_SESSION['totalPrice'];
        // Folosește $totalPrice cum dorești pe această pagină
    } else {
        echo "Sesiunea pentru totalPrice nu este setată.";
    }
    
    if ($stmt) {
        $stmt->bind_param("idsis", $user_id, $price, $address, $user_email, $phone_no);
        $stmt->execute();
        $stmt->close();

        // Eliberează produsele din coș după plasarea comenzii
        unset($_SESSION['cart']);

        // Redirecționează către o pagină de confirmare sau altă destinație
        header("Location: order_confirmation.php");
        exit;
    } else {
        echo "Eroare la pregătirea interogării de adăugare comandă: " . $conn->error;
    }
}

// Calculați prețul total aici
$totalPrice = calculateTotalPrice($_SESSION['shopping_cart']);

$conn->close();
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="css/style_order.css?<?php echo time();?>"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@1,300&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="body">

<div class="navbar">
    <div class="logo">
        <a href="index.php"><img src="imagini/logo.png" alt="logo" width="125px"></a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="aboutus.php">About Us</a></li>
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<li><a href="logout.php">Logout</a></li>';
                echo '<li><a href="cart.php">Shopping Cart</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>
<div class="container">
    <h2>Plasare Comandă:</h2>
    <form method="post" action="">
        <label for="address">Adresă de livrare:</label>
        <input type="text" name="address" required>

        <label for="phone_no">Număr de telefon:</label>
        <input type="text" name="phone_no" required>
        <p>Preț Total: <?php echo number_format($totalPrice, 2); ?> RON</p>


        <button type="submit">Plasează Comanda</button>
    </form>
</div>
</body>
</html>