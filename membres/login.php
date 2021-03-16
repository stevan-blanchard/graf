<?php session_start(); ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>

<?php
require('../navigation/top-left-nav.php');
require('../sql/config.php');
echo "hello";
?>

<main>
    <div class="index_col_center">
        <?php
        if (isset($_POST['connexion'])) {

            $username = htmlentities($_POST['username-mail']);
            $password = htmlentities($_POST['password']);
//          recupération du mot de passe de par le pseudo ou le mail entrée dans l'input
            $reqPassword = $dbh->prepare("SELECT * FROM membre WHERE mail = ? OR pseudo = ?");
            $reqPassword->execute([$username, $username]);
            $user = $reqPassword->fetch();

//          récupération du pseudo pour vérifier si il existe
            $reqPseudo = $dbh->prepare('SELECT * FROM membre WHERE pseudo = ?');
            $reqPseudo->execute(array($username));
            $pseudoExist = $reqPseudo->rowCount();

//          récupération du mail pour vérifier si il existe
            $reqmail = $dbh->prepare('SELECT * FROM membre WHERE mail = ?');
            $reqmail->execute(array($username));
            $mailexist = $reqmail->rowCount();

//            si input rempli et egale mail ou pseudo, on continue
            if (!empty($mailexist == 1) or !empty($pseudoExist == 1)) {
//                vérification que le mot de passe correspond bien au pseudo/mail entrée
                if (!empty(password_verify($password, $user['password']))) {
                    $authorId = $user['author_id'];
                    $reqNameAuthor = $dbh->prepare("SELECT name FROM author WHERE id = '$authorId'");
                    $reqNameAuthor->execute();
                    $authName = $reqNameAuthor->fetch();
                    $_SESSION['authorName'] = $authName['name'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['mail'] = $user['mail'];
                    header("location: ../index.php");
                    exit;

                } else {
                    $error = "Le mot de passe ne correspond pas";
                }
            } else {
                $error = "Un des champs n'est pas colmplété";
            }
        }
        ?>
        <form action="" method="post" name="login">
            <h2 class="box-title">Connexion</h2>

            <div>
                <label for="username-mail"></label> <br>
                <input type="text" name="username-mail" id="username-mail"
                       placeholder="Votre pseudo ou votre mail">
            </div>
            <div>
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Mot de passe">
            </div>
            <div>
                <input type="submit" value="Connexion " name="connexion">
            </div>
            <p class="box-register">Vous êtes nouveau ici? <a href="form-inscription.php">S'inscrire</a></p>
            <?php if (isset($error)) { ?>
                <p class="errorMessage"><?php echo $error; ?></p>
            <?php } ?>
        </form>
    </div>
</main>

</body>
</html>