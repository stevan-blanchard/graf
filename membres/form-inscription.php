<?php session_start(); ?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <title>Inscription</title>
</head>
<body>
<?php
require "../navigation/top-left-nav.php";
?>

<?php

require "../sql/config.php";

if (isset($_POST["inscription-membres"])) {

    //verification que les champs ne sont pas vides et qu'ils comportent bien des lettres et des chiffres sans esapces
    if (
        !empty($_POST["pseudo"])
        and !empty($_POST["author"])
        and !empty($_POST["mail"])
        and !empty($_POST["mail2"])
    ) {


        $pseudo = $_POST["pseudo"];
        $author = $_POST["author"];
        $mail = $_POST["mail"];
        $mail2 = $_POST["mail2"];
//        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
//        $password2 = password_verify($_POST["password2"], $password);

        //vérification que pseudo et auteur comporte que des lettre et chiffre
        if (preg_match("/^[a-zA-Z0-9 ]+$/", $pseudo) and preg_match("/^[a-zA-Z0-9 ]+$/", $author)) {

            //vérification que le pseudo n'existe pas déjà dans la base de donnée
            $reqPseudo = $dbh->prepare('SELECT * FROM membre WHERE pseudo = ?');
            $reqPseudo->execute(array($pseudo));
            $pseudoExist = $reqPseudo->rowCount();

            if ($pseudoExist == 0) {

                //vérification que la taille du pseudo et de l'auteur ne dépasse pas 255 caractères
                $pseudoLength = strlen($pseudo);
                $authorLength = strlen($author);

                if ($pseudoLength <= 255 and $authorLength <= 255) {

                    //vérification que mail et mail2 sont identiques
                    if ($mail == $mail2) {

                        //vérification que mail est bien un EMAIL !
                        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

                            //vérification que le mail n'est pas déjà présent dans la base de donnée
                            $reqmail = $dbh->prepare('SELECT * FROM membre WHERE mail = ?');
                            $reqmail->execute(array($mail));
                            $mailexist = $reqmail->rowCount();

                            if ($mailexist == 0) {
                                if (!empty($_POST["password"])
                                    and preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/", $_POST["password"])) {

                                    if (!empty($_POST['password2'])) {
                                        //vérification que les mots de passe sont identiques
                                        if ($_POST["password"] == $_POST["password2"]) {

                                            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                                            $password2 = password_verify($_POST["password2"], $password);

                                            //ajout du nom d'auteur dans la table author
                                            $insertAuth = $dbh->prepare("INSERT INTO author (name) VALUE (?)");
                                            $insertAuth->execute(array($author));

                                            //récupération de l'id de l'ateur add
                                            $reqAuthor = $dbh->lastInsertId();

                                            //ajout du membre si tout c'est bien passé
                                            $insertmbr = $dbh->prepare("INSERT INTO membre (author_id, pseudo, mail, password) VALUE (?,?, ?, ?)");
                                            $insertmbr->execute(array($reqAuthor, $pseudo, $mail, $password));

                                            header("location: ../redirection/redirection-inscription.php");
                                            exit;

                                        } else {
                                            $error = 'Les mots de passe ne sont pas identiques';
                                        }

                                    } else {
                                        $error = "Il manque le mot de passe de confirmation";
                                    }
                                } else {
                                    $error = "Il y a une erreur dans la création de votre mot de passe";
                                }
                            } else {
                                $error = "Votre adresses mail n'est pas valide";
                            }
                        } else {

                            $error = "Vos adresse mail ne correspond pas";
                        }
                    } else {

                        $error = "Votre pseudo ou le nom d'auteur est/sont trop long";
                    }

                } else {

                    $error = "Ce pseudo exist déjà";
                }

            } else {
                $error = "le pseudo et/ou le nom d'auteur ne doivent pas contenir d'espaces";
            }

        } else {

            $error = "Tous les champs doivent être remplis";
        }

    }
}
?>

<div class="index_col_center">

    <h2>Inscription</h2>

    <form action="" method="post">
        <table class="table-center">
            <tr>
                <td><label class="label-form-mbr" for="pseudo">Pseudo: </label></td>
                <td><input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo"></td>
            </tr>
            <tr>
                <td><label for="author">Auteur: </label></td>
                <td><input type="text" name="author" id="author" placeholder="Votre nom d'auteur"></td>
            </tr>
            <tr>
                <td><label for="mail">Mail: </label></td>
                <td><input type="email" name="mail" id="mail" placeholder="Votre adresse mail"></td>
            </tr>
            <tr>
                <td><label for="mail2">Confirmation du mail: </label></td>
                <td><input type="email" name="mail2" id="mail2" placeholder="Confirmez votre adresse mail"></td>
            </tr>
            <tr>
                <td><label for="password">Mot de passe <br>(6 caractère minimum. <br> 1 Lettre majuscule & 1 chiffre):
                    </label></td>
                <td><input type="password" name="password" id="password" placeholder="Votre mot de passe"></td>
            </tr>
            <tr>
                <td><label for="password2">Confirmation mot de passe: </label></td>
                <td><input type="password" name="password2" id="password2" placeholder="confirmez votre mot de passe">
                </td>
            </tr>
        </table>
        <br>
        <br>
        <input type="submit" value="Inscription" name="inscription-membres">
    </form>
    <br>
    <br>
    <div class="error">
        <?php
        if (isset($error)) {
            echo "<i class='fas fa-exclamation-triangle'></i>" . "  " . $error . '  ' . "<i class='fas fa-exclamation-triangle'></i>";
        }
        ?>
    </div>

</div>
</body>
</html>
