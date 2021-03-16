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
    <title>Edition RAF</title>
</head>
<body>
<?php
require '../navigation/top-left-nav.php';
require '../sql/config.php';

//Récupération de l'id
if (isset($_GET['id']) && !preg_match('/[0-9]/', $_GET['id'])) {
    require_once('../redirection/problem.php');
} else {
$id = $_GET['id'];

$reqEdit = $dbh->prepare("SELECT * FROM RAF WHERE id = '$id'");
$reqEdit->execute();
$edit = $reqEdit->fetch();
$requete ="";
?>
<main>
    <?php
    
    if (isset($_POST["envoyer"])) {
        if (!empty($_POST["author"])) {
            /**  fonction pour vérifier:
             * trim() = Supprime les espaces
             * stripslashes () = Supprime les antislashs d'une chaîne
             * htmlspecialchars =  Convertit les caractères spéciaux en entités HTML
             * preg_replace = suppression des espaces en trop
             */
            function verification($donnees)
            {
                $donnees = trim($donnees);
                $donnees = stripslashes($donnees);
                $donnees = htmlspecialchars($donnees);
                $donnees = preg_replace("/\s+/", " ", $donnees);
                return $donnees;
            }

            $author = verification($_POST["author"]);
            $description = verification($_POST["description"]);
            $hours =  $_POST["hours"];
            $minutes =  $_POST["minutes"];
            $priority = (int)$_POST["priority"];
            $includeIn = verification($_POST["includeIn"]);
            $deadline = $_POST["deadline"];
            $oneThird = isset($_POST['one_third']) ? $_POST['one_third'] : NULL;
            $twoThird = isset($_POST['two_third']) ? $_POST['two_third'] : NULL;
            $finish = isset($_POST['finish']) ? $_POST['finish'] : NULL;
            $observation = verification($_POST["observation"]);

//Vérification que les champs ne sont pas vides
//verification avec la fonction
            if
            (!empty ($author)
                and ($description)
                and ($priority) <= 4
            ) {
//            verification que la date est bien au format 'years-month-day'
                if (isset($deadline) == preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $deadline)) {
//                verification que l'heure est bien au format 'hours : minutes'
                    if (!empty(is_numeric($hours) AND $hours>0) AND !empty(is_numeric($minutes) AND $minutes>=0)) {

                        $duration = $hours.".".$minutes;

                        //recupération de l'id "author"
                        $checkAuthors = $dbh->prepare("SELECT `id` FROM author WHERE name ='" . $_POST["author"] . "'");
                        $checkAuthors->execute();
                        $checkAuthor = $checkAuthors->fetch();
                        $checkAuthors->closeCursor();
//                        verification de l'écriture "include dans le projet
                        if (!empty ($includeIn) and preg_match("/^[a-zA-Z0-9 ]+$/", $includeIn)) {
//                          recupération de l'id "includeInProject" ////// pas besoin de else car peu être null :!
                            $reqIds = $dbh->prepare("SELECT `id` FROM includeInProjects WHERE name ='" . $_POST["includeIn"] . "'");
                            $reqIds->execute();
                            $reqId = $reqIds->fetch();
                        }

                        //si observation est rempli ↓
                        if (!empty($observation)) {
                            $okObservarion = $observation;
                            //si observation est vide ↓
                        } else $okObservarion = NULL;

//                        si la case du 2/3 est cochée il faudra aussi que 1/3 soit marquée comme fait
                        if (isset($twoThird) == 1) {
                            $oneThird = 1;
                        }
//                        si la case du 100% est cochée il faudra que toutes les autres le soient aussi
                        if (isset($finish) == 1) {
                            $oneThird = 1;
                            $twoThird = 1;
                        }
                        //sauvegarde de l'ancienne RAF
                        $unT = 0;
                        $deuxT = 0;
                        $troisT = 0;
                        $today = date("Y-m-d H:i:s");
                        if($edit['un_tiers'] == 1){
                            $unT = 1;
                        }
                        if($edit['deux_tiers'] == 1){
                            $deuxT = 1;
                        }
                        if($edit['trois_tiers'] == 1){
                            $troisT = 1;
                        }
                        $save_req = $dbh->prepare
                        ("INSERT INTO `old_RAF` (`raf_id`,`author_id`, `description`, `duration`, `priority`, `deadline`, `includeInProject_id`, `un_tiers`, `deux_tiers`, `trois_tiers`, `observation`,`date_modif`)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

                        //modification de la nouvelle 

                        $reqRaf = $dbh->prepare
                        ("UPDATE RAF SET
                                author_id = ?,
                                description = ?,
                                duration = ?,
                                priority = ?,
                                deadline = ?,
                                includeInProject_id = ?,
                                un_tiers = ?,
                                deux_tiers = ?,
                                trois_tiers = ?,
                                observation = ?
                                WHERE ID = '$id';");
                        $reqRaf->execute(array(
                            $checkAuthor['id'],
                            $description,
                            $duration,
                            $priority,
                            $deadline,
                            $reqId['id'],
                            $oneThird,
                            $twoThird,
                            $finish,
                            $okObservarion
                        ));
                        $save_req->execute(array(
                            $id,
                            $edit['author_id'],
                            $edit['description'],
                            $edit['duration'],
                            $edit['priority'],
                            $edit['deadline'],
                            $edit['includeInProject_id'],
                            $unT,
                            $deuxT,
                            $troisT,
                            $edit['observation'],
                            $today));

                        header("location: ../redirection/redirection-edit-raf.php");
                        exit;

                    } else {
                        $error = "Attention au format de la durée";
                    }
                } else {
                    $error = "probleme date";
                }
            } else {
                $error = "Touts les champs sont obligatoires";
            }
        } else {
            $error = "Créer un compte utilisateur, avec un nom d'auteur avant de commencer";
        }
    }
    ?>

    <div class="index_col_center">
        <h2>Modification du reste à faire</h2>           
        <div class="error">
            <?php
            $unT = 0;
            $deuxT = 0;
            $troisT = 0;
            $today = date("Y-m-d H:i:s");
            if($edit['un_tiers'] == 1){
                $unT = 1;
            }
            if($edit['deux_tiers'] == 1){
                $deuxT = 1;
            }
            if($edit['trois_tiers'] == 1){
                $troisT = 1;
            }
            if (isset($error)) {
                echo "<i class='fas fa-exclamation-triangle'></i>" . "  " . $error . '  ' . "<i class='fas fa-exclamation-triangle'></i>";
            }
            ?>
        </div>
        <br>
        <br>
        <form action="" method="post">
            <div class="col_form_left">
                <label for="author"><b>Choisir votre nom d'auteur*</b></label>
                <br>
                <!--                selection de par la list des auteurs de la datebase-->
                <?php
                $authorSelect = $dbh->prepare("SELECT * FROM author");
                $authorSelect->execute();

                echo "<select name='author' id='author'>";
                while ($authorList = $authorSelect->fetch()) {
//                    affichage du nom de l'auteur en rapport avec le raf à modifier
                    if ($authorList['id'] == $edit["author_id"]) {
                        $selected = "selected";
                    } else {
                        $selected = NULL;
                    }
                    ?>
                    <option value="<?php echo htmlspecialchars($authorList["name"]); ?> " <?php echo $selected ?>>
                        <?php echo htmlspecialchars($authorList["name"]); ?>
                    </option>

                <?php }
                echo "</select>";
                ?>
                <div class="label-input-raf-form">
                    <label for="description"><b>Déscription de l'action prévue* </b></label>
                    <br>
                    <textarea rows="4" cols="54" name="description"
                              id="description"><?php echo htmlentities($edit['description']) ?></textarea>
                </div>
                <?php
                $minutes = substr($edit['duration'], -2);
                $hours = substr($edit['duration'], 0, 2);
                ?>


                <div class="label-input-raf-form">
                    <label><b>Durée nécessaire*</b></label>
                    <br>
                    <label for='hours'>heure(s):</label>
                    <input id='hours' name='hours' type='number' size="2" min='0' value="<?php echo (int)$hours; ?>">

                    <input id='minutes' name='minutes' type='number' max='59' size="2" value="<?php echo (int)$minutes; ?>">
                    <label id="minutes">:minutes</label>
                </div>

                <div class="label-input-raf-form">
                    <label for="priority"><b>Priorité de 1 à 4*</b><br>
                        (Sachant que 4 est le plus haut niveau de priorité) </label>
                    <br>
                    <input type="number" min="1" max="4" name="priority" id="priority"
                           value="<?php echo $edit['priority']; ?>">
                </div>

            </div>
            <div class="col_form_right">
                <div class="label-input-raf-form">
                    <label><b>Voulez vous inclure l'action dans une catégorie?</b></label>
                    <br>
                    <?php
                    $includeSelect = $dbh->prepare("SELECT * FROM `includeInProjects`");
                    $includeSelect->execute();

                    echo '<select name="includeIn">';

                    while ($includeList = $includeSelect->fetch()) {
                    //                    affichage du nom de l'auteur en rapport avec le raf à modifier
                    if ($includeList['id'] == $edit["includeInProject_id"]) {
                        $selected = "selected";
                    } else {
                        $selected = NULL;
                    }
                    ?>
                    <option value="<?php echo htmlentities($includeList['name']) ?>" <?php echo $selected ?>>
                        <?php echo htmlentities($includeList['name']) . '</option>';

                        }
                        echo '</select>' ?>
                </div>

                <div class="label-input-raf-form">
                    <label for="deadline"><b>Date d'échéance*</b></label>
                    <br>
                    <input type="date" name="deadline" id="deadline" value="<?php echo $edit['deadline'] ?>">
                </div>

                <div class="label-input-raf-form">
                    <div class="advancement">
                        <label><b>Avancement actuel</b></label> <br>
                        <?php if ($edit['un_tiers'] == 1) {
                            $checkOne = "checked";
                        } else {
                            $checkOne = NULL;
                        }
                        if ($edit['deux_tiers'] == 1) {
                            $checkTwo = "checked";
                        } else {
                            $checkTwo = NULL;
                        }
                        if ($edit['trois_tiers'] == 1) {
                            $checkthree = "checked";
                        } else {
                            $checkthree = NULL;
                        }
                        ?>
                        <input type="checkbox" id="one_third" name="one_third" value="1" <?php echo $checkOne ?> >
                        <label for="one_third"> 1/3 </label><br>
                        <input type="checkbox" id="two_third" name="two_third" value="1" <?php echo $checkTwo ?>>
                        <label for="two_third"> 2/3 </label><br>
                        <input type="checkbox" id="finish" name="finish" value="1" <?php echo $checkthree ?>>
                        <label for="finish">100%</label><br>
                    </div>
                </div>

                <div class="label-input-raf-form">
                    <label for="observation"><b>Observation(s)</b><br>
                        Les précisions sur la réalisartion doivent rester dans la première case. <br>
                        Ici il s'agit juste de préciser la raison éventuelle à prendre en compte sur la date butoir.
                        <br>
                        Exemple: est-elle non negociable?
                        <br>(L'observation empêchera toutes modifications du reste à faire)</label>
                    <br>
                    <textarea rows="4" cols="54" name="observation"
                              id="observation"><?php echo htmlentities($edit['observation']) ?></textarea>
                </div>
            </div>

            <div class="submit label-input-raf-form">
                <label><i>Les champs marqués d'une * sont obligatoires</i></label><br>
                <input type="submit" name="envoyer" value="Entrer les données">
            </div>
        </form>

        <?php } ?>
</main>
</body>
</html>