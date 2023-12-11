<?php
session_start();
include("phpscripts/connections.php");

// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
function shorten_description($description, $length = 100) {
    if (strlen($description) > $length) {
        $description = substr($description, 0, $length) . '...';
    }
    return $description;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Afișare produse</title>
    <link rel="stylesheet" href="css/style_afisare.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@1,300&display=swap" rel="stylesheet">

    <script>
        function toggleDescription(element) {
            var product = element.parentNode;
            var description = product.querySelector('.description');
            var fullDescription = product.querySelector('.full-description');

            if (description.style.display === 'none') {
                description.style.display = 'block';
                fullDescription.style.display = 'none';
                element.innerText = 'Citește mai mult';
                product.classList.remove('active');
            } else {
                description.style.display = 'none';
                fullDescription.style.display = 'block';
                element.innerText = 'Citește mai puțin';
                product.classList.add('active');
            }
        }

        function addToCart(productId) {
            if (sessionStorage.getItem('cart') === null) {
                sessionStorage.setItem('cart', JSON.stringify([]));
            }

            let cart = JSON.parse(sessionStorage.getItem('cart'));
            cart.push(productId);
            sessionStorage.setItem('cart', JSON.stringify(cart));

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'addToCart=' + productId,
            })
            .then(response => response.text())
            .then(message => {
                console.log(message);
              
            });
        }

        // AJAX request for handling search without refreshing the page
        function searchProducts(query) {
            fetch('search.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'query=' + query,
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('productContainer').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            searchProducts('');
        });
    </script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="index.php"><img src="imagini/logo.png" alt="logo" width="125px"></a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <?php
                // Verificați dacă utilizatorul este autentificat
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    echo '<li><a href="logout.php">Logout</a></li>';
                    echo '<a href="cart.php"><img src="imagini/cart.png" alt="logo" width="22px"></a>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <div class="search-container">
        <form action="home.php" method="POST">
            Search <input type="text" name="search"><br>
            <input type="submit">
        </form>
    </div>

    <div class="container">
        <?php
        // Check if a search query is submitted
        if (isset($_POST['search'])) {
            $searchQuery = $_POST['search'];

            // Escapare pentru a preveni SQL injection
            $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

            // Interogare pentru căutare în baza de date
            $sql = "SELECT * FROM store WHERE nume LIKE '%$searchQuery%' OR descriere LIKE '%$searchQuery%'";
            $result = $conn->query($sql);

            // Afiseaza rezultatele cautarii
            echo '<h2>Rezultatele căutării pentru: ' . htmlspecialchars($searchQuery) . '</h2>';

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
        } else {
            // Selectează toate produsele din baza de date
            $sql = "SELECT * FROM store";
            $result = $conn->query($sql);

            // Afiseaza toate produsele
            echo '<h2>Produse disponibile:</h2>';
            echo '<div class="row">';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product">';
                    echo '<img src="imagini/' . $row["imagine"] . '" alt="' . $row["nume"] . '">';
                    echo '<h4>' . $row["nume"] . '</h4>';
                    echo '<p class="description">' . shorten_description($row["descriere"]) . '</p>';
                    echo '<p class="full-description" style="display: none;">' . $row["descriere"] . '</p>';
                    echo '<p class="read-more" onclick="toggleDescription(this)">Citește mai mult</p>';
                    echo '<button class="add-to-cart-btn" onclick="addToCart(' . $row["id"] . ')">Adaugă în coș</button>';
                    echo '</div>';
                }
            } else {
                echo "Nu există produse în baza de date.";
            }

            echo '</div>';
        }
        ?>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>
