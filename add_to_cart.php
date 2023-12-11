<?php
session_start();

include("phpscripts/connections.php");

// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

if (isset($_POST['addToCart'])) {
    $productId = $_POST['addToCart'];

    // Verificăm dacă cheia "id" există în $_SESSION
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Verificăm dacă produsul există deja în coșul utilizatorului
        $checkProductQuery = "SELECT * FROM shopping_cart WHERE user_id = ? AND product_id = ?";
        $stmtCheckProduct = $conn->prepare($checkProductQuery);

        if ($stmtCheckProduct) {
            $stmtCheckProduct->bind_param("ii", $userId, $productId);
            $stmtCheckProduct->execute();
            $resultCheckProduct = $stmtCheckProduct->get_result();

            if ($resultCheckProduct->num_rows > 0) {
                // Produsul există deja în coș, așa că actualizăm cantitatea
                $updateQuantityQuery = "UPDATE shopping_cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
                $stmtUpdateQuantity = $conn->prepare($updateQuantityQuery);

                if ($stmtUpdateQuantity) {
                    $stmtUpdateQuantity->bind_param("ii", $userId, $productId);
                    $stmtUpdateQuantity->execute();
                    $stmtUpdateQuantity->close();
                } else {
                    echo "Eroare la pregătirea interogării de actualizare a cantității: " . $conn->error;
                }
            } else {
                // Produsul nu există în coș, așa că îl adăugăm
                $insertQuery = "INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
                $stmtInsert = $conn->prepare($insertQuery);

                if ($stmtInsert) {
                    $stmtInsert->bind_param("ii", $userId, $productId);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                } else {
                    echo "Eroare la pregătirea interogării de inserare: " . $conn->error;
                }
            }

            $stmtCheckProduct->close();
        } else {
            echo "Eroare la pregătirea interogării de verificare a produsului: " . $conn->error;
        }
    } else {
        echo "Cheia 'id' lipsește din sesiune.";
    }
}

$conn->close();
?>
