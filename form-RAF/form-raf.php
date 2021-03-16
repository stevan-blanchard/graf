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
    <title>Nouveau RAF</title>
</head>
<body>
<?php require '../navigation/top-left-nav.php' ?>
<?php require '../sql/config.php' ?>

<?php
//date de la journée
$date = date("Y-m-d");

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
        $hours = $_POST["hours"];
        $minutes = $_POST["minutes"];
        $priority = (int)$_POST["priority"];
        $includeIn = verification($_POST["includeIn"]);
        $deadline = $_POST["deadline"];
        $oneThird = isset($_POST['one_third']) ? $_POST['one_third'] : NULL;
        $twoThird = isset($_POST['two_third']) ? $_POST['two_third'] : NULL;
        $observation = verification($_POST["observation"]);

//Vérification que les champs ne sont pas vides
//verification avec la fonction
        if
        (!empty ($author)
            and ($description)
            and ($priority) <= 4
        ) {
//            verification que la date est bien au format 'years-month-day'
            if (isset($deadline) == preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $deadline)){
//                verification que l'heure est bien au format 'hours : minutes'
                if (!empty(is_numeric($hours) and $hours >= 0) and !empty(is_numeric($minutes) and $minutes >= 0)) {

                    $duration = $hours . "." . $minutes;

                    //recupération de l'id "author"
                    $checkAuthors = $dbh->prepare("SELECT `id` FROM author WHERE name ='" . $_POST["author"] . "'");
                    $checkAuthors->execute();
                    $checkAuthor = $checkAuthors->fetch();
                    $checkAuthors->closeCursor();
//                        verification de l'écriture "include dans le projet
                    if (!empty ($includeIn) and preg_match("/^[a-zA-Z0-9 ]+$/", $includeIn)) {
                        //recupération de l'id "includeInProject" ////// pas besoin de else car peu être null :!
                        $reqIds = $dbh->prepare("SELECT `id` FROM includeInProjects WHERE name ='" . $_POST["includeIn"] . "'");
                        $reqIds->execute();
                        $reqId = $reqIds->fetch();
                    }

                    //si observation est rempli ↓
                    if (!empty($observation)) {
                        $okObservarion = $observation;
                        //si observation est vide ↓
                    } else $okObservarion = NULL;

//                  si la case du 2/3 est cochée il faudra aussi que 1/3 soit marquée comme fait
                    if (isset($twoThird) == 1) {
                        $oneThird = 1;
                    }


                    $reqRaf = $dbh->prepare
                    ("INSERT INTO
    `RAF`(`author_id`, `description`, `duration`, `priority`, `deadline`, `includeInProject_id`, `un_tiers`, `deux_tiers`, `observation`)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
                    $reqRaf->execute(array(
                        $checkAuthor['id'],
                        $description,
                        $duration,
                        $priority,
                        $deadline,
                        $reqId['id'],
                        $oneThird,
                        $twoThird,
                        $okObservarion
                    ));

                    header("location: ../redirection/redirection-newRAF.php");
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
    <h2>Nouveau "Reste à Faire"</h2>

    <div class="error">
        <?php
        if (isset($error)) {
            echo "<i class='fas fa-exclamation-triangle'></i>" . "  " . $error . '  ' . "<i class='fas fa-exclamation-triangle'></i>";
        }
        ?>
    </div>
    <br>
    <br>
    <form action="" method="post">
        <div class="col_form_left">
            <div class="label-input-raf-form">
                <label for="author"><b>Choisir votre nom d'auteur*</b></label>
                <br>
                <?php
                $authorSelect = $dbh->prepare("SELECT name FROM author");
                $authorSelect->execute();
                echo "<select name='author' id='author'>";
                while ($authorList = $authorSelect->fetch()) { ?>
                    <option value="<?php echo htmlspecialchars($authorList["name"]); ?> ">
                        <?php echo htmlspecialchars($authorList["name"]); ?>
                    </option>

                <?php }
                echo "</select>";
                ?>
            </div>
            <div class="label-input-raf-form">
                <label for="description"><b>Déscription de l'action prévue* </b></label>
                <br>
                <textarea rows="4" cols="54" name="description" id="description"></textarea>
            </div>

            <div class="label-input-raf-form">
                <label><b>Durée nécessaire*</b></label>
                <br>
                <label for='hours'>heure(s):</label>
                <input id='hours' name='hours' type='number' size="2" min='0' value="0">

                <input id='minutes' name='minutes' type='number' max='59' size="2" value="0">
                <label id="minutes">:minutes</label>
            </div>

            <div class="label-input-raf-form">
                <label for="priority"><b>Priorité de 1 à 4*</b><br>
                    (Sachant que 4 est le plus haut niveau de priorité) </label>
                <br>
                <input type="number" min="1" max="4" name="priority" id="priority">
            </div>

        </div>
        <div class="col_form_right">
            <div class="label-input-raf-form">
                <label><b>Dans quelle catégorie inclure l'action?</b></label>
                <br>
                <?php
                $includeSelect = $dbh->prepare("SELECT * FROM `includeInProjects`");
                $includeSelect->execute();

                echo '<select name="includeIn">';

                while ($includeList = $includeSelect->fetch()) { ?>
                <option value="<?php echo htmlentities($includeList['name']) ?>">
                    <?php echo htmlentities($includeList['name']) . '</option>';

                    }
                    echo '</select>' ?>
            </div>

            <div class="label-input-raf-form">
                <label for="deadline"><b>Date d'échéance*</b></label>
                <input type="date" name="deadline" id="deadline">
            </div>

            <div class="label-input-raf-form">
                <label><b>Avancement actuel</b></label> <br>
                <input type="checkbox" id="one_third" name="one_third" value="1">
                <label for="one_third"> 1/3 </label><br>
                <input type="checkbox" id="two_third" name="two_third" value="1">
                <label for="two_third"> 2/3 </label><br>
            </div>

            <div class="label-input-raf-form">
                <label for="observation"><b>Observation(s)</b><br>
                    Les précisions sur la réalisartion doivent rester dans la première case. <br>
                    Ici il s'agit juste de préciser la raison éventuelle à prendre en compte sur la date butoir. <br>
                    Exemple: est-elle non negociable?
                    <br>(L'observation empêchera toutes modifications du reste à faire)</label>
                <br>
                <textarea rows="4" cols="54" name="observation" id="observation"></textarea>
            </div>
        </div>

        <div class="submit label-input-raf-form">
            <label><i>Les champs marqués d'une * sont obligatoires</i></label><br>
            <input type="submit" name="envoyer" value="Entrer les données">
        </div>
    </form>
    <br>
    <br>

</div>

</body>
</html>