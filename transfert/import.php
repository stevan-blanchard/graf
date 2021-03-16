<?php
session_start();
if (isset($_SESSION['id'])) {
    $sessionID = $_SESSION['id'];
} else {
    $sessionID = NULL;
}
$_version = 1.5;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.css" media="screen"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- JS -->
    <script type="text/javascript" src="js/jQuery.js"></script>
    <script type="text/javascript" src="jquery-ui/jquery-ui.js"></script>

    <title>Import/change pdp photo</title>
</head>
<body>
<?php
require '../navigation/top-left-nav.php';
?>
    <div class="index_col_center">
        </br>
        </br>
        </br>
        <h3>selectionner la nouvelle photo d'export pour le pdf (jpeg, jpg, png)</h3>
            <form enctype="multipart/form-data" action="#" method="post">
                <input type="hidden" name="MAX_FILE_SIZE" value="25000000" />
                <input type="file" name="monfichier" size=50 />
                <input type="submit" value="Envoyer" />
            </form>

            
    </div>
    <?php
        $nomOrigine = $_FILES['monfichier']['name'];
        $elementsChemin = pathinfo($nomOrigine);
        $extensionFichier = $elementsChemin['extension'];
        $extensionsAutorisees = array("jpeg", "jpg", "png");
        if (!(in_array($extensionFichier, $extensionsAutorisees))) {
            ?>
                <div class="index_col_center">
                    <h7>il n'y a pas de fichier ou le fichier n'a pas l'extension attendue
                </div>
            <?php
            //echo "il n'y a pas de fichier ou le fichier n'a pas l'extension attendue";
        } else {    
            // Copie dans le repertoire du script avec un nom
            // incluant l'heure a la seconde pres 
            $repertoireDestination = __DIR__."/../managment/export/img/";

            //on supprime l'ancienne photo
            $delete_old = scandir("./../managment/export/img/");
            foreach($delete_old as $photo){
                unlink("./../managment/export/img/".$photo);
            }
            //on ajoute la nouvelle photo
            $nomDestination = "logo.".$extensionFichier;

            if (move_uploaded_file($_FILES["monfichier"]["tmp_name"],$repertoireDestination.$nomDestination)) {
                ?>
                <div class="index_col_center">
                    <h7>les changements ont été pris en compte
                </div>
                <?php
            } else {
                ?>
                <div class="index_col_center">
                    <h7>Le fichier n'a pas été uploadé (trop gros ?) ou 
                    </br>
                    <h7>Le déplacement du fichier temporaire a échoué
                </div>
            <?php
            }
        }
        ?>
</body>
</html>