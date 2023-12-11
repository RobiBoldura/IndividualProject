<?php
session_start();

include("phpscripts/connections.php");
include("phpscripts/connections.php");
include("phpscripts/functions.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpscripts/vendor/autoload.php';
// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Verificare dacă utilizatorul este autentificat

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Obține ID-ul utilizatorului autentificat
    $userId = $_SESSION['id'];

    // Interogare pentru a obține produsele din coșul de cumpărături ale utilizatorului
    $cartQuery = "SELECT shopping_cart.id as cart_id, shopping_cart.product_id, store.id as product_id, store.nume, store.pret, store.imagine, shopping_cart.quantity FROM shopping_cart
                  JOIN store ON shopping_cart.product_id = store.id
                  WHERE shopping_cart.user_id = ?";
    $stmtCart = $conn->prepare($cartQuery);

    if ($stmtCart) {
        $stmtCart->bind_param("i", $userId);
        $stmtCart->execute();
        $resultCart = $stmtCart->get_result();
        $stmtCart->close();
    } else {
        echo "Eroare la pregătirea interogării de coș de cumpărături: " . $conn->error;
    }
} else {
    echo '<p>Utilizatorul nu este autentificat.</p>';
}

// Funcție pentru calculul prețului total
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalPrice = calculateTotalPrice($_SESSION['cart']);
} else {
    $totalPrice = 0;
}
function calculateTotalPrice($cart) {
    
    $totalPrice = 0;

    foreach ($cart as $product) {
        $totalPrice += $product['pret'] * $product['quantity'];
    }

    return $totalPrice;
}
// Verificare dacă a fost trimisă o cerere POST pentru ștergerea unui element din coș

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalPrice = 0;
    if (isset($_POST['delete'])) {
        $cart_id = $_POST['cart_id'];

        // Șterge elementul din coș
        $delete_query = "DELETE FROM shopping_cart WHERE id = ? AND user_id = ?";
        $stmtDelete = $conn->prepare($delete_query);

        if ($stmtDelete) {
            $stmtDelete->bind_param("ii", $cart_id, $userId);
            $stmtDelete->execute();
            $stmtDelete->close();

            // Redirecționează către pagina cart.php
            header("Location: cart.php");
            exit();
        } else {
            echo "Eroare la pregătirea interogării de ștergere din coș: " . $conn->error;
        }
    } elseif (isset($_POST['place_order'])) {
        // Procesare plasare comandă
        $user_id = $_SESSION['id'];
        $address = $_POST['address'];
        $phone_no = $_POST['phone_no'];
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $totalPrice = calculateTotalPrice($_SESSION['cart']);
        // Afisează valorile pentru depanare
        echo "User ID: " . $user_id . "<br>";
        echo "Address: " . $address . "<br>";
        echo "Phone Number: " . $phone_no . "<br>";
        echo "User Email: " . $user_email . "<br>";
        echo "Total Price: " . $totalPrice . "<br>";

        // Calculăm prețul total
      

        // Adăugăm comanda în tabela "orders"
        $insertOrderQuery = "INSERT INTO orders (user_id, price, address, user_email, phone_no) VALUES (?, ?, ?, ?, ?)";
        $stmtInsertOrder = $conn->prepare($insertOrderQuery);

        if ($stmtInsertOrder) {
            $stmtInsertOrder->bind_param("issss", $user_id, $totalPrice, $address, $user_email, $phone_no);
            $stmtInsertOrder->execute();
            $stmtInsertOrder->close();
        
            // Eliberăm produsele din coș după plasarea comenzii
            $clear_cart_query = "DELETE FROM shopping_cart WHERE user_id = $user_id";
            $clear_cart_result = mysqli_query($conn, $clear_cart_query);
             $mail = new PHPMailer();
             $mail->isSMTP();
             $mail->Host = 'smtp.gmail.com';
             $mail->SMTPAuth = true;
             $mail->Username = 'robertboldura@gmail.com'; // Your Gmail username
             $mail->Password = 'ldbs gzll pkcn ufop'; // Your Gmail app password
             $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
             $mail->Port = 465;
        $mail->Subject = 'Comanda plasata cu succes';
        $mail->Body    = 'Mulțumim pentru comandă! Comanda ta a fost plasată cu succes.';
        $mail->AltBody = 'Mulțumim pentru comandă! Comanda ta a fost plasată cu succes.';
        $mail->addAddress($user_email, 'Utilizatorul tău');

        if (!$mail->send()) {
            echo 'Eroare la trimiterea e-mailului: ' . $mail->ErrorInfo;
        } else {
            echo 'Comandă plasată cu succes! Un e-mail de confirmare a fost trimis la ' . $user_email;
        }
        
            // Redirecționăm către o pagină de confirmare sau altă destinație
           
           // Schimbă aceasta cu pagina dorită
            exit();
        } else {
            echo "Eroare la pregătirea interogării de adăugare comandă: " . $conn->error;
        }
    }
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Inițializează 'cart' ca un array gol
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coșul de cumpărături</title>
    <link rel="stylesheet" href="css/style_cart.css?<?php echo time();?>"/>
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
    <h2>Coșul de cumpărături:</h2>
    <div class="product-list">
        <?php
        if ($resultCart->num_rows > 0) {
            while ($rowCart = $resultCart->fetch_assoc()) {
                $productTotalPrice = $rowCart['pret'] * $rowCart['quantity'];
                $totalPrice += $productTotalPrice;

                echo '<div class="product">';
                echo '<img class="product-image" src="imagini/' . $rowCart["imagine"] . '" alt="' . $rowCart["nume"] . '">';
                echo '<h4>' . $rowCart["nume"] . '</h4>';
                echo '<p>Quantity: ' . $rowCart['quantity'] . '</p>';
                echo '<p>Total Price: ' . $productTotalPrice . ' RON</p>';

                // Verificați dacă cheia "cart_id" există înainte de a încerca să o accesați
                echo '<form method="POST" action=""><input type="hidden" name="cart_id" value="' . $rowCart['cart_id'] . '">
<button type="submit" name="delete">Remove from Cart</button>
</form>';

                echo '</div>';
            }
        } else {
            echo '<p class="message">Coșul de cumpărături este gol.</p>';
        }
        ?>
    </div>

    <!-- Adăugă formularul pentru plasarea comenzii -->
    <div class="order-form">
        <form method="POST" action="">
            <label for="address">Adresă de livrare:</label>
            <input type="text" name="address" required>

            <label for="phone_no">Număr de telefon:</label>
            <input type="text" name="phone_no" required>

            <label for="email">Adresă de email:</label>
            <input class="text-input" type="email" name="email" required><br><br>

            <p>Preț Total: <?php echo number_format($totalPrice, 2); ?> RON</p>

            <button type="submit" name="place_order">Plasează Comanda</button>
        </form>
    </div>
</div>

<footer class="footer">
    <!-- Adăugați conținutul footer-ului aici, dacă este necesar -->
</footer>
</body>
</html>