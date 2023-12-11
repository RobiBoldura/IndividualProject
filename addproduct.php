<?php
// Configurați informațiile de conectare la baza de date
include("phpscripts/connections.php");

// Conectare la baza de date
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Conectați-vă la baza de date
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Verifică dacă utilizatorul are ID-ul 1
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
        // Verifică dacă este trimis un formular de adăugare a produsului
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preluați valorile introduse în formular
    $nume = $_POST["nume"];
    $descriere = $_POST["descriere"];
    $pret = $_POST["pret"];

    // Verificați dacă a fost selectată o imagine
    if (isset($_FILES['imagine'])) {
        $image_name = $_FILES['imagine']['name'];
        $image_tmp = $_FILES['imagine']['tmp_name'];
        $image_size = $_FILES['imagine']['size'];

        // Verificați dacă fișierul este o imagine
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_exts = array("jpg", "jpeg", "png", "gif");

        if (in_array($image_ext, $allowed_exts)) {
            // Definiți calea și numele fișierului de destinație
            $target_dir = "imagini/";
            $target_file = $target_dir . basename($image_name);

            // Mută fișierul încărcat în locația dorită
            move_uploaded_file($image_tmp, $target_file);

            // Inserați produsul în baza de date împreună cu numele imaginii
            $sql = "INSERT INTO store (nume, descriere, pret, imagine) VALUES ('$nume', '$descriere', '$pret', '$image_name')";
            if ($conn->query($sql) === TRUE) {
                echo "Produsul a fost adăugat cu succes.";
            } else {
                echo "Eroare la adăugarea produsului: " . $conn->error;
            }
        } else {
            echo "Eroare: Fișierul încărcat nu este o imagine validă.";
        }
    } else {
        echo "Eroare: Nu a fost selectată o imagine.";
    }
}
}else {
    // Utilizatorul nu este autorizat, puteți redirecționa sau afișa un mesaj de eroare
    echo "Accesul interzis! Doar utilizatorului cu cu rol de administrator i se permite să adauge produse.";
}
} else {
    // Utilizatorul nu este autentificat, puteți redirecționa sau afișa un mesaj de eroare
    echo "Utilizatorul nu este autentificat.";
}
// Selectați toate produsele din baza de date
$sql = "SELECT * FROM store";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <!-- adăugați aici orice fișiere CSS sau alte elemente head necesare -->
    <link rel="stylesheet" href="css/style_adaug.css">
    <!-- Adaugă în head după link-urile CSS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Adaugă în body după container -->
<form id="addToCartForm" method="post" action="add_to_cart.php">
    <input type="hidden" id="productId" name="addToCart" value="">
</form>

<script>
    function addToCart(productId) {
        // Setează valoarea input-ului hidden cu ID-ul produsului
        $("#productId").val(productId);

        // Trimite formularul către add_to_cart.php
        $("#addToCartForm").submit();
    }
</script>
</head>
<body>
<div class="navbar">
<div class="logo">
    <a href="index.php"><img src="imagini/logo.png" alt="logo" width="150px"></a>
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
    <a href="cart.php"><img src="imagini/cart.png" alt="cart" width="30px" height="30px"></a>
</div>

<div class="container">
    <!-- formularul de adăugare a produsului -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <label for="nume">Nume:</label>
    <input type="text" name="nume" required><br><br>
    <label for="descriere">Descriere:</label>
    <textarea name="descriere" required></textarea><br><br>
    <label for="pret">Preț:</label>
    <input type="number" name="pret" required><br><br>
    <label for="imagine">Imagine:</label>
    <input type="file" name="imagine" accept="image/*" required><br><br>
    <input type="submit" value="Adăugare produs">
</form>

    <!-- afișarea produselor existente din baza de date -->
    <h2>Produse disponibile:</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>Nume:</strong> " . $row["nume"] . " | <strong>Preț:</strong> " . $row["pret"] . "</li>";
            }
        } else {
            echo "<li>Nu există produse în baza de date.</li>";
        }
        ?>
    </ul>
</div>

</body>
</html>
