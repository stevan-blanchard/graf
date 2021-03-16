<?php
/*
 Licence : CeCILL_V2.1
 Auteur(s) : Tourneux Cédric(auteur original) Blanchard Stevan (amélioration)
*/
session_start();
if (isset($_SESSION['diagram'])) {
    $display = $_SESSION['diagram'];
} else {
    $display = 0;
}
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
    <!-- JS !-->
    <script type="text/javascript" src="tri-tableau.js"></script>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Diagramme</title>
</head>
<body>
<header>
    <?php
    require '../navigation/top-left-nav.php';
    require "../sql/config.php";
    ?>
</header>
<main>
    <div class="index_col_center-diagramme">
        <h2><a href="diagramme-time-left.php">Diagramme</a></h2>
        <!--    selection du nom de l'auteur pour ensuite afficher son diagramme-->
        <form action="" method="post">
            <label for="authors">Affichage par auteur: </label>
            <?php

            $authorSelect = $dbh->prepare("SELECT id, name FROM author");
            $authorSelect->execute();
            echo "<select name='authors' id='authors'>";
            $tous = 0;
            ?>
            <option value="<?php echo (int)$tous ?>">Tous</option>

            <?php
            while ($authorList = $authorSelect->fetch()) {?>
                <option value="<?php echo (int)($authorList["id"]); ?> ">
                    <?php echo htmlspecialchars($authorList["name"]); ?>
                </option>
            <?php }
            echo "</select>";
            ?>
            <input type="submit" name="afficher" value="Afficher">
        </form>
        <?php
        if (isset($_POST["afficher"])) {
            $idAuthor = (int)$_POST["authors"];
            //Vérification que les champs ne sont pas vides
            //verification que c'est bien un chiffre
            if
            (isset ($idAuthor)) {
                $auth = $idAuthor;
                /**         si ok, écriture dans un fichier text
                 *          le chiffre correspond à l'id d'un auteur
                 *          0 = tous ; qui affichera tout les RAFS sans tri d'auteur*/

                $_SESSION['diagram'] = $auth;
                header("location: diagramme-time-left.php");
                exit;
            }
        }
        ?><br>
        <h4> Reste à faire de<?php
            if ($display == 0) {
                echo " tous le monde";
            } else {
                $h3 = $dbh->prepare("SELECT name FROM author WHERE id = '$display'");
                $h3->execute();
                $name = $h3->fetch();
                echo " " . $name['name'];
            } ?></h4>

        <!--    ligne rassemblant les trois diagrammes-->

            <div class="diaTime">
                <!--        diagramme de gauche-->
                <div class="pie-left">
                    <?php
                    $jour = date("d-m-Y");
                    $d9 = strtotime($jour . "+ 35 days");
                    ?>
                    <div class="text-justifty"> Date d'échéance dépassée <i class="fas fa-level-down-alt"></i></div>
                    <?php
                    include 'pie-left.php';
                    include 'under-pie-left.php';
                    ?>
                </div>
                <!--        diagramme central en bar-->
                <div class="dia-center">
                    <?php include 'diagram-central.php' ?>
                </div>
                <!--        diagramme de droite -->
                <div class="pie-right">
                    <?php
                    $jour = date("d-m-Y");
                    $d9 = strtotime($jour . "+ 39 days");
                    ?>
                    <div class="text-justifty">Date d'échéance à partir du<br>
                        <?php echo date("d-m-Y", $d9); ?><i class="fas fa-level-down-alt"></i></div>
                    <?php include 'pie-right.php' ?>
                    <?php include 'under-pie-right.php' ?>

                </div>
            </div>

            <!--    sous les diagrammes, le tableau des RAFs-->
            <?php
            //Selection de tout ce qu'il y a dans les restes à faire
            $displayRaf = $dbh->prepare("SELECT * FROM RAF");
            $displayRaf->execute();
            if (($display) == 0) {
                $displayRaf = $dbh->prepare("SELECT * FROM RAF");
                $displayRaf->execute();
            } elseif (($display)) {
                $displayRaf = $dbh->prepare("SELECT * FROM RAF WHERE author_id = '$display'");
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
                echo "</tr>";
            }
            echo "</tbody>";


            echo "</table>";

            
	    ?>
         
</main>
</body>
</html>