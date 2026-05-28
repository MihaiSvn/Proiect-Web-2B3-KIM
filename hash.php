<?php
// hash.php
$parola_dorita = "hash_12345"; // Pune aici ce parolă vrei
$hash = password_hash($parola_dorita, PASSWORD_DEFAULT);

echo "<h1>Parola ta este: $parola_dorita</h1>";
echo "<p>Copiaza acest hash in scriptul tau SQL:</p>";
echo "<code>" . $hash . "</code>";