<?php
session_start();
if (isset($_SESSION['id'])) {
    $sessionID = $_SESSION['id'];
} else {
    $sessionID = NULL;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>Nouvelle catégorie de projet</title>
</head>
<body>
<?php
require '../navigation/top-left-nav.php';
require "../sql/config.php";
?>
<main>
    <div class="index_col_center">
        <h2>Nouvelle catégorie de projet</h2>
        <?php

        if (isset($_POST['new_cat'])) {
            $cat_project = htmlspecialchars($_POST['cat_project']);
            $color = $_POST['color'];
            if (!empty($cat_project)) {
                $checkColors = $dbh->prepare("SELECT * FROM includeInProjects WHERE color = ?");
                $checkColors->execute(array($color));
                $checkColor = $checkColors->rowCount();

                if (!empty(preg_match('/^#[a-f0-9]{6}$/i', $color)) and $checkColor == 0 and $color != '#000000') {
                $includeInProject = $dbh->prepare("INSERT INTO includeInProjects (name, color) VALUES (?,?)");
                $includeInProject->execute(array($cat_project, $color));

                header('location:../redirection/newcategorie.php');
                exit();
                } else {
                    $error = 'il y a un problème avec la couleur';
                }
            } else {
                $error = "Attention";
            }
        }
        ?>

        <form action="" method="post">
            <label for="cat_project">Nouvelle catégorie de projet:</label>
            <input type="text" name="cat_project" id="cat_project"> <br> <br>

            <label for="color">Entre une couleur</label>
            <input type="color" id="color" name="color"> <br><br>

            <input type="submit" name="new_cat" value="Enregistrer">
        </form>

        <?php if (isset($error)) {
            echo $error;
        }

        $reqCats = $dbh->prepare("SELECT * FROM includeInProjects");
        $reqCats->execute();

        ?>
        <div class="addCategories"><b>Les différentes catégories de projets sont:</b> <br>

            <?php while ($cats = $reqCats->fetch()) {
                echo $cats['name'] . ": "   .  ' <div class="addColor" style="background-color:'.$cats["color"] .'">' .'</div>';
 }?>



        </div>

    </div>
</main>
</body>
</html>