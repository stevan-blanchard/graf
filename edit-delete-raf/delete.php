<?php
require '../sql/config.php';

/* pour la suppression du reste à faire choisi en cliquant sur le petite poubelle */
if(isset($_GET['id']) && !preg_match('/[0-9]/', $_GET['id'])) {
    require_once('../redirection/problem.php');
} else {
    $id = $_GET['id'];

    $reqdelete = $dbh->prepare("DELETE FROM `RAF` WHERE id = '$id'");
    $reqdelete->execute();
    header("location: ../index.php");
    exit();
}

?>
