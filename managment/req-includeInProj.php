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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="../jquery-ui/jquery-ui.css" media="screen"/>
    <!-- JS -->
    <script type="text/javascript" src="../js/jQuery.js"></script>
    <script type="text/javascript" src="../jquery-ui/jquery-ui.js"></script>

    <title>Modifications des catégories</title>
</head>
<body>
<?php
require '../navigation/top-left-nav.php';
require '../sql/config.php';

$reqDelIncl = $dbh->prepare("SELECT * FROM includeInProjects");
$reqDelIncl->execute();
?>
<main>
    <div class="index_col_center">
        <h2>Liste des catégories de projet</h2>
        <p>(La suppression sera irréversible)</p>
        <div class="del-managment">

            <h4> Liste des catégories: </h4>
            <?php
            echo '<table class="table-managment">'; ?>
            <th>Noms</th>
            <th>Modifier</th>
            <th>Supprimer</th>
            <th>Exporter</th>

            <?php
            while ($delIncl = $reqDelIncl->fetch()) {
                if ($delIncl['name'] != "aucune") {

                    $includeId = $delIncl['id'];
                    echo "<tr>";

                    ?>
                    <td class="td_name">
                        <i class="fas fa-angle-right"></i> <?php echo ' ' . $delIncl['name'] ?>
                    </td>
                    <td>
                        <a href="edit-includeInprojet.php?id=<?php echo $includeId; ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a class="confirmModal"  href="del-includeIn.php?id=<?php echo $includeId; ?>">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td>
                    <a href="./export/export-includeInprojet.php?id=<?php echo $includeId; ?>" target="_blank">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                    
                    <br>
                    <?php
                    echo "</tr>";
                }
            }
            echo "</table>"; ?>
            </br>
            </br>
            <li><a href="../transfert/import.php">Changer l'image d'exportation</a></li>
            
        </div>
        <!--script pour confirmer ou non la suppression d'un reste à faire-->
        <script>
            $(document).ready(function () {

                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: 160,
                    width: 500,
                    autoOpen: false,
                    modal: true,
                    buttons: {
                        "Oui": function () {
                            $(this).dialog("close");
                            window.location.href = theHREF;
                        },
                        "Annuler": function () {
                            $(this).dialog("close");
                        }
                    }
                });

                $("a.confirmModal").click(function (e) {
                    e.preventDefault();
                    theHREF = $(this).attr("href");
                    $("#dialog-confirm").dialog("open");
                });
            });

        </script>
        <div id="dialog-confirm" title="Confirmation de la suppression" style="display:none;">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                Etes-vous sûr de vouloir supprimer cet élément ?
            </p>
        </div>
    </div>
</main>
</body>
</html>