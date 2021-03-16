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
    <title>Modification du nom d'auteur</title>
</head>
<body>

<?php
require '../navigation/top-left-nav.php';
require '../sql/config.php';

if (isset($_GET['id']) && preg_match('/[0-9]/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('location: ../redirection/problem.php');
    exit();
}
$reqNames = $dbh-> prepare("SELECT * FROM includeInProjects WHERE id = '$id'");
$reqNames-> execute();

$reqName = $reqNames->fetch();
?>

<main>
    <div class="index_col_center">
        <h2>Modification</h2>
        <form action="" method="post">
            <label for="name"></label>
            <input type="text" name="name" id="name" value="<?php echo $reqName['name'] ?>"> <br>

            <label for="color">Entre une couleur</label>
            <input type="color" id="color" name="color" value="<?php echo $reqName['color']?>"> <br><br>

            <input type="submit" name="ok" value="Enregistrer">
        </form>

        <?php if (isset($_POST['ok'])) {
            if (!empty($_POST['name']) AND preg_match("/^[a-zA-Z]+$/", $_POST['name'])) {
                $name = $_POST['name'];
                if (!empty(preg_match('/^#[a-f0-9]{6}$/i', $_POST['color']))) {
                    $color = $_POST['color'];

                    $reqUpdateName = $dbh->prepare("UPDATE `includeInProjects` SET `name` = ?, `color` = ? WHERE `id` = '$id'");
                    $reqUpdateName->execute(array($name, $color));

                    header("location: ../index.php");
                    exit();
                } else {
                    header('location: ../redirection/problem.php');
                    exit();
                }
            }  else {
                header('location: ../redirection/problem.php');
                exit();
            }
        }

        ?>
    </div>
</main>
</body>
</html>