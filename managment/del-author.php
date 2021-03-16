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

    $reqDeleteRaf = $dbh->prepare("DELETE FROM `RAF` WHERE author_id = '$id'");
    $reqDeleteRaf->execute();

    $reqDeleteMember = $dbh->prepare("DELETE FROM `membre` WHERE author_id = '$id'");
    $reqDeleteMember->execute();

    $reqDeleteAuthor = $dbh->prepare("DELETE FROM `author` WHERE id = '$id' ");
    $reqDeleteAuthor->execute();

    if ($id = $sessionID) {
        session_destroy();
        header("location: ../index.php");
        exit();
    } else {
        header("location: ../index.php");
        exit();
    }

}  else {
    header('location: ../redirection/problem.php');
    exit();
}

?>
