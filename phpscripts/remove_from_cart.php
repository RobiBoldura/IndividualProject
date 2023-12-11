<?php
session_start();

include("connections.php");

// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obține datele trimise prin POST
    $userId = $_POST['user_id'];
    $productId = $_POST['product_id'];

    // Șterge produsul din coș
    $deleteQuery = "DELETE FROM shopping_cart WHERE user_id = ? AND product_id = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    $stmtDelete->bind_param("ii", $userId, $productId);
    $stmtDelete->execute();

    $stmtDelete->close();
}

$conn->close();
?>
