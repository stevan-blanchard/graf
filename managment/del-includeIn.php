<?php
session_start();
if (isset($_SESSION['id'])) {
    $sessionID = $_SESSION['id'];
} else {
    $sessionID = NULL;
}

require '../sql/config.php';

if (isset($_GET['id']) && preg_match('/[0-9]/', $_GET['id'])) {
    $id = $_GET['id'];

    //TODO ACHIVAGE du projet
    
    //

    $reqDelIncl = $dbh->prepare("DELETE FROM `includeInProjects` WHERE `id` = '$id'");
    $reqDelIncl->execute();

    header("location: ../index.php");
    exit();

}  else {
    header('location: ../redirection/problem.php');
    exit();
}

?>
