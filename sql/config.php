<?php
require 'connexion.php';
try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">
  Problème de connexion à la base de données.
</div>';
    echo $e;
    exit;
}

