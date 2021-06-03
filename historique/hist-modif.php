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
        <h2>historique des Restes à faire</h2>

        <form action="./hist-modif.php">
        <label for="auteur">Affichage par auteur:</label>

        <select name="auteur" id="auteur">
	<option value="-1">---sélectionner un auteur---</option>
            <?php
                $authorSelect = $dbh->prepare("SELECT id, name FROM author");
                $authorSelect->execute();
                while($display = $authorSelect->fetch()){
                    echo '<option value="'.$display['id'].'">'.$display['name'].'</option>';
                }


            ?>
        </select>
	</br>
        <label for="projet">Affichage par projet:</label>
        <select name="projet" id="projet">
	<option value="-1">---sélectionner un projet---</option>
            <?php
                $authorSelect = $dbh->prepare("SELECT id, name FROM includeInProjects");
                $authorSelect->execute();
                while($display = $authorSelect->fetch()){
                    echo '<option value="'.$display['id'].'">'.$display['name'].'</option>';
                }


            ?>
        </select>
	</br>
        <label for="auteur">Affichage par pourcentagge de finition :</label>
	<input type="radio" id="13" name="un_tiers" value="1">
  	<label for="13">1/3</label>
	<input type="radio" id="23" name="deux_tiers" value="1">
        <label for="23">2/3</label>
	<input type="radio" id="33" name="trois_tiers" value="1">
        <label for="33">3/3</label><br>

	</br>
	<label for="auteur">date limite dépassée:</label>
        <input type="radio" id="limit_yes" name="limit_yes" value="1">
	<label for="limit_yes">oui</label>
	</br>
        <input type="submit" value="Submit">
	</form>
        </br>
        </br>

        <?php
        if($_GET["auteur"] != NULL || $_GET["projet"] != NULL){
	    $req = "SELECT * FROM old_RAF where 1=1 ";
	    if($_GET["auteur"] != -1){$req.="AND author_id = ".$_GET['auteur']." ";}
	    if($_GET["projet"] != -1){$req.="AND includeInProject_id = ".$_GET['projet']." ";}
	    if($_GET["un_tiers"] != NULL){$req.="AND un_tiers = ".$_GET['un_tiers']." ";}
	    if($_GET["deux_tiers"] != NULL){$req.="AND deux_tiers = ".$_GET['deux_tiers']." ";}
	    if($_GET["trois_tiers"] != NULL){$req.="AND trois_tiers = ".$_GET['trois_tiers']." ";}
      	    if($_GET["limit_yes"] != NULL){$req.="AND deadline <= now() ";}
            $displayRaf = $dbh->prepare($req);
            $displayRaf->execute();
        }
	else {
	$displayRaf = $dbh->prepare("SELECT * FROM old_RAF;");
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
