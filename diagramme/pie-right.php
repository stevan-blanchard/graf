<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="application/javascript" src="diagramme/rpie.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Pie Right</title>
</head>
<body>
<?php
//----------------------------------------------------------------------------------------------------------------------
// dans 5 semaines++

//si tous le monde est séléctionné
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
        $arrayRight[] = $heightCatRight; //création d'un array pour regroupper tous les résultats
    }
    //somme de l'array
    $sumRight = array_sum($arrayRight);
    //100 = 100% la base du diagram pie et on enlève la taille des restes à faire dépassés
    $valueRight = (100 - ($sumRight));
    //implode de l'array pour un resulta: ex: 10,50,10,20 ...etc
    $right = implode($arrayRight, ", ");

    $color = implode($catColors, ", ");
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
        $arrayRight[] = $heightCatRight; //création d'un array pour regroupper tous les résultats
    }
    //somme de l'array
    $sumRight = array_sum($arrayRight);
    //100 = 100% la base du diagram pie et on enlève la taille des restes à faire dépassés
    $valueRight = (100 - ($sumRight));
    //implode de l'array pour un resulta: ex: 10,50,10,20 ...etc
    $right = implode($arrayRight, ", ");

    $color = implode($catColors, ", ");
//    echo $right;
}
//----------------------------------------------------------------------------------------------------------------------

$sumLeft = array_sum($arrayRight);

if ($sumLeft > 100){
    $display = "display: none;";
} else {
$display = "display: block;";
?>
<div class="pie-right-php" style=" <?php echo $display ?>">
    <canvas id="myCanvas2" width="200" height="200"></canvas>
</div>

<!--Base de la value = 100-->
<script type="text/javascript">
    var obj2 = {
        values: [<?php echo $valueRight ?> ,<?php echo $right ?>],
        colors: ['#191919', <?php echo $color ?>],
        animation: true,
        animationSpeed: 5, // Time in miliisecond & default animation speed is 20ms
        // fillTextData: true,
        fillTextColor: '#fff',
        fillTextPosition: 'horizontal',
        doughnutHoleSize: null,
        doughnutHoleColor: '#fff',
        offset: null
    };
    //Generate myCanvas3
    generatePieGraph('myCanvas2', obj2);
</script>

<?php } ?>
</body>
</html>