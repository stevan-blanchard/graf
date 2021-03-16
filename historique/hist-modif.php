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
    <!-- JS !-->
    <script type="text/javascript" src="../diagramme/tri-tableau.js"></script>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>historique des modifications</title>
</head>
<body>
<header>
    <?php
    require '../navigation/top-left-nav.php';
    require "../sql/config.php";
    ?>
</header>
<main>
    <div class="index_col_center">
        <h2><a href="index.php">historique des Restes à faire </a></h2>

        <form action="./hist-modif.php">
        <label for="auteur">Affichage par auteur:</label>

        <select name="auteur" id="auteur">
            <?php
                $authorSelect = $dbh->prepare("SELECT id, name FROM author");
                $authorSelect->execute();
                while($display = $authorSelect->fetch()){
                    echo '<option value="'.$display['id'].'">'.$display['name'].'</option>';
                }


            ?>
        </select>
        <input type="submit" value="Submit">
        </form>

        <form action="./hist-modif.php">
        <label for="projet">Affichage par projet:</label>

        <select name="projet" id="projet">
            <?php
                $authorSelect = $dbh->prepare("SELECT id, name FROM includeInProjects");
                $authorSelect->execute();
                while($display = $authorSelect->fetch()){
                    echo '<option value="'.$display['id'].'">'.$display['name'].'</option>';
                }


            ?>
        </select>
        <input type="submit" value="Submit">
        </form>

        </br>
        </br>

        <?php
        if($_GET["auteur"] != NULL && $_GET["projet"] == NULL){
            $displayRaf = $dbh->prepare("SELECT * FROM old_RAF where author_id = ".$_GET['auteur'].";");
            $displayRaf->execute();
        }
        else if($_GET["auteur"] == NULL && $_GET["projet"] != NULL){
            $displayRaf = $dbh->prepare("SELECT * FROM old_RAF where includeInProject_id = ".$_GET['projet'].";");
            $displayRaf->execute();
        }
        else{
            $displayRaf = $dbh->prepare("SELECT * FROM old_RAF");
            $displayRaf->execute();
        }
        ?>

        <table id='tab' class='table-sort'>
            <thead>
            <tr>
             <th class='td1'>Auteur ↕️</th>
             <th class='td2'>Durée ↕️</th>
             <th class='td3'>Dans le projet ↕️</th>
             <th class='td7'>Priorité ↕️</th>
             <th class='td4'>Déscription ↕️</th>
             <th class='td5'>Observation ↕️</th>
             <th class='td6'>1/3 ↕️</th>
             <th class='td7'>2/3 ↕️</th>
             <th class='td8'>Terminer ↕️</th>
             <th class='td9'>Date de fin ↕️</th>
             <th class='td10'>Jours J ↕️</th>
             <th width: 200px>date modif ↕️</th>
             </tr>
             </thead><tbody>
             <?php
             while ($display = $displayRaf->fetch(PDO::FETCH_ASSOC)) {

//Récupération du nom de l'auteur
                $authorNames = $dbh->prepare("SELECT * FROM author WHERE id ='" . $display['author_id'] . "'");
                $authorNames->execute();
                $authorName = $authorNames->fetch();
//Récupération de nom du projet
                $includeNames = $dbh->prepare("SELECT * FROM includeInProjects WHERE id ='" . $display['includeInProject_id'] . "'");
                $includeNames->execute();
                $includeName = $includeNames->fetch();

//calcul jour restant avant la deadline
                $today = date("Y-n-j");
                $origin = date_create($today);
                $target = date_create($display['deadline']);
                $interval = date_diff($origin, $target);
                $dDay = $interval->format('%R%a days');


//Avancement 1/3
                if ($display['un_tiers'] == 1) {
                    $dOThird = '<i class="fas fa-check-circle fasCheckDia" style="line-height: inherit"></i>';
                } else {
                    $dOThird = "";
                }
//Avancement 2/3
                if ($display['deux_tiers'] == 1) {
                    $dTThird = '<i class="fas fa-check-circle fasCheckDia"></i>';
                } else {
                    $dTThird = "";
                }
//Avancement fini
                if ($display['trois_tiers'] == 1) {
                    $dFThird = '<i class="fas fa-check-circle fasCheckDia"></i>';
                } else {
                    $dFThird = "";
                }


//Couleur de fond des jours restants
//        if ($dDay <= 0)

                if ($dDay > -1 && $dDay < 3) {
                    $tdColor = '<td class="td10 td-yellow">';
                } elseif ($dDay > 2) {
                    $tdColor = '<td class="td10 td-green">';
                } else {
                    $tdColor = '<td class="td10 td-red">';
                }

                $bg = $includeName["color"];

//Tableau des données des restes à faire
                echo "<tr>";
                echo "<td class='td1'>" . $authorName["name"] . "</td>";

                echo "<td class='td2'>" . str_replace('.', 'h', $display['duration']) . "</td>";

                echo "<td class='td3' style='background-color: $bg '>" . $includeName["name"] . "</td>";

                echo '<td class="td2">' . $display['priority'] . "</td>";
                echo "<td class='td4'>" . $display['description'] . "</td>";

                echo "<td class='td5' >" . $display['observation'] . "</td>";

                echo "<td class='td6' >" . $dOThird . "</td>";
                echo "<td class='td7' >" . $dTThird . "</td>";
                echo "<td class='td8' >" . $dFThird . "</td>";

                echo "<td class='td9' >" . $display['deadline'] . "</td>";
                
                echo " $tdColor" . $dDay . "</td>";

                echo "<td class='td11' >" . $display['date_modif'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";


            echo "</table>";
	    ?>
        </div>
</main>
</body>