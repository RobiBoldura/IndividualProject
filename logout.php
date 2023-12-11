<?php
session_start();

// Setare variabile de sesiune pentru delogare
$_SESSION['loggedin'] = false;
// Alte variabile de sesiune relevante pentru delogare

// Distrugerea sesiunii
session_destroy();

// Redirecționare către pagina de succes sau pagina principală
header("Location: index.php");
exit();
?>