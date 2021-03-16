<?php
session_start();
if (isset($_SESSION['id'])) {
    $sessionID = $_SESSION['id'];
} else {
    $sessionID = NULL;
}
$_version = 1.7;
?>

<?php
# ***** AVERTISSEMENT SUR LA LICENSE *****
# Copyleft Tourneux Cédric, 2021
#
# Version: 1.7
#
# Adresse mail : cedric.tourneux@gmail.com
#
# Ce logiciel est un programme informatique servant à la gestion
# des restes à faire, remplacents le fichier Excel.
#
# Ce logiciel est régi par la licence CeCILL soumise au droit français et
# respectant les principes de diffusion des logiciels libres. Vous pouvez
# utiliser, modifier et/ou redistribuer ce programme sous les conditions
# de la licence CeCILL telle que diffusée par le CEA, le CNRS et l'INRIA
# sur le site "http://www.cecill.info".
#
# En contrepartie de l'accessibilité au code source et des droits de copie,
# de modification et de redistribution accordés par cette licence, il n'est
# offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons,
# seule une responsabilité restreinte pèse sur l'auteur du programme,  le
# titulaire des droits patrimoniaux et les concédants successifs.
#
# A cet égard  l'attention de l'utilisateur est attirée sur les risques
# associés au chargement,  à l'utilisation,  à la modification et/ou au
# développement et à la reproduction du logiciel par l'utilisateur étant
# donné sa spécificité de logiciel libre, qui peut le rendre complexe à
# manipuler et qui le réserve donc à des développeurs et des professionnels
# avertis possédant  des  connaissances  informatiques approfondies.  Les
# utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
# logiciel à leurs besoins dans des conditions permettant d'assurer la
# sécurité de leurs systèmes et ou de leurs données et, plus généralement,
# à l'utiliser et l'exploiter dans les mêmes conditions de sécurité.
#
# Le fait que vous puissiez accéder à cet en-tête signifie que vous avez
# pris connaissance de la licence CeCILL, et que vous en avez accepté les
# termes.
# ***** AVERTISSEMENT SUR LA LICENSE *****
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.css" media="screen"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- JS -->
    <script type="text/javascript" src="js/jQuery.js"></script>
    <script type="text/javascript" src="jquery-ui/jquery-ui.js"></script>

    <title>GRAF</title>
</head>
<body>
<?php
require 'navigation/top-left-nav.php';
require "sql/config.php";
?>

<main>
    <div class="index_col_center">
        <h2><a href="index.php">Restes à faire</a></h2>
        <p id="color-priority">Signification contours:</p>
        <div class="color-priority">
            <div>Priorité 1 : <div class="addColor" style="background-color:green"></div></div>
            <div>Priorité 2 : <div class="addColor" style="background-color:yellow"></div></div>
            <div>Priorité 3 : <div class="addColor" style="background-color:darkorange"></div></div>
            <div>Priorité 4 : <div class="addColor" style="background-color:red"></div></div>
        </div>
        <?php
        if ($_GET["individual"] == "oui"){
            include 'index/card-raf-individual.php';
        }
        else{
            include 'index/card-raf.php';
        }
        ?>

        </div>
</main>
</body>
</html>