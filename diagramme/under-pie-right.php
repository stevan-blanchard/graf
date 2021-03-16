<div class="cat_under_pie">
<?php

if ($display == 0) {

//récupération de l'id de la catégorie de projet
$reqCat = $dbh->prepare("SELECT * from includeInProjects");
$reqCat->execute();
//boucle pour afficher toutes les catégories de projets
while ($cat = $reqCat->fetch()) {
$catID = $cat['id'];
$catName[] = $cat['name'];
$catColors[] = "'".$cat['color']."'";

$timeRight = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
FROM RAF
WHERE DATE_ADD(CURDATE(), INTERVAL 39 DAY) < `deadline` AND `includeInProject_id` = '$catID'");
$timeRight->execute();
$resultRight = $timeRight->fetch();
$heightCatRight = ($resultRight["TOTAL"]*1);
    if ($heightCatRight != 0) {
        echo '<ul class="no-padding">'
            . ' <li class="cat_under_pie_color" style="background-color:'
            . $cat['color']
            . '">'
            . '</li>'
            . $cat['name']
            . ': '
            . "$heightCatRight"
            . " heures"
            . '</d>';
    } else {
        $heightCatLeft = NULL;
    }

}

} else {
//récupération de l'id de la catégorie de projet
$reqCat = $dbh->prepare("SELECT * from includeInProjects");
$reqCat->execute();
//boucle pour afficher toutes les catégories de projets
while ($cat = $reqCat->fetch()) {
$catID = $cat['id'];
$catName[] = $cat['name'];
$catColors[] = "'".$cat['color']."'";

$timeRight = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
FROM RAF
WHERE DATE_ADD(CURDATE(), INTERVAL 39 DAY) < `deadline`
AND `includeInProject_id` = '$catID'
AND author_id = '$display' ");
$timeRight->execute();
$resultRight = $timeRight->fetch();
$heightCatRight = ($resultRight["TOTAL"]*1);
    if ($heightCatRight != 0) {
        echo '<ul class="no-padding">'
            . ' <li class="cat_under_pie_color" style="background-color:'
            . $cat['color']
            . '">'
            . '</li>'
            . $cat['name']
            . ': '
            . "$heightCatRight"
            . " heures"
            . '</ul>';
    } else {
        $heightCatLeft = NULL;
    }

}

} ?>
</div>