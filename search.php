<?php
include("phpscripts/connections.php");

// Check if the connection is successful
if ($con->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $con->connect_error);
}

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    // Escapare pentru a preveni SQL injection
    $searchQuery = mysqli_real_escape_string($con, $searchQuery);

    // Interogare pentru căutare în baza de date
    $sql = "SELECT * FROM store WHERE nume LIKE '%$searchQuery%' OR descriere LIKE '%$searchQuery%'";
    $result = $con->query($sql);

    // Afiseaza rezultatele cautarii
    echo '<div class="container">';
    echo '<h2>Rezultatele căutării:</h2>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<img src="imagini/' . $row["imagine"] . '" alt="' . $row["nume"] . '">';
            echo '<h4>' . $row["nume"] . '</h4>';
            echo '<p class="description">' . shorten_description($row["descriere"]) . '</p>';
            echo '<p class="full-description" style="display: none;">' . $row["descriere"] . '</p>';
            echo '<p class="read-more" onclick="toggleDescription(this)">Citește mai mult</p>';
            
            // Add the product to cart form
            echo '<form action="home.php" method="POST">';
            echo '<input type="hidden" name="addToCart" value="' . $row["id"] . '">';
            echo '<button type="submit" class="add-to-cart-btn">Adaugă în coș</button>';
            echo '</form>';

            echo '</div>';
        }
    } else {
        echo "Nu s-au găsit rezultate pentru căutarea: " . htmlspecialchars($searchQuery);
    }

    echo '</div>';
}

$con->close();
?>
