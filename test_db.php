<?php
// test_db.php

include 'db.php'; // Include your database connection file

$query = $pdo->query("SELECT * FROM users"); // Query to fetch users

if ($query) {
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "Connected successfully. Users: <br>";
    foreach ($users as $user) {
        echo $user['username'] . "<br>";
    }
} else {
    echo "No users found.";
}
?>

