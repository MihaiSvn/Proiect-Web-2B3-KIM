<?php
// test_db.php

global $pdo;
try {
    $stmt = $pdo->query("SELECT * FROM USERS");
    $users = $stmt->fetchAll();

    echo "<h1>Connection works</h1>";
    echo "Number of users connected: " . count($users);

} catch (PDOException $e) {
    echo "<h1>Error at query</h1> " . $e->getMessage();
}