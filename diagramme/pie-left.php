<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="application/javascript" src="rpie.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>Pie Left</title>
</head>
<body>
<?php
//----------------------------------------------------------------------------------------------------------------------
//diagramme rond gauche (dates dépassées)

//si tous le monde est séléctionné
if ($display == 0) {

    //récupération de l'id de la catégorie de projet
    $reqCat = $dbh->prepare("SELECT * from includeInProjects");
    $reqCat->execute();
    //boucle pour afficher toutes les catégories de projets
    while ($cat = $reqCat->fetch()) {
        $catID = $cat['id'];
        $catName[] = $cat['name'];
        $catColors[] = "'" . $cat['color'] . "'";

        $timeLeft = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE  `deadline` < CURDATE()
                      AND `includeInProject_id` = '$catID'");
        $timeLeft->execute();
        $resultLeft = $timeLeft->fetch();
        $heightCatLeft = ($resultLeft["TOTAL"] * 1);
        $arrayLeft[] = $heightCatLeft; //création d'un array pour regroupper tous les résultats
    }
    //somme de l'array
    $sumLeft = array_sum($arrayLeft);
    //100 = 100% la base du diagram pie et on enlève la taille des restes à faire dépassés
    $valueLeft = (100 - ($sumLeft));
    //implode de l'array pour un resulta: ex: 10,50,10,20 ...etc
    $left = implode($arrayLeft, ", ");

    $color = implode($catColors, ", ");
} else {
    //récupération de l'id de la catégorie de projet
    $reqCat = $dbh->prepare("SELECT * from includeInProjects");
    $reqCat->execute();
    //boucle pour afficher toutes les catégories de projets
    while ($cat = $reqCat->fetch()) {
        $catID = $cat['id'];
        $catName[] = $cat['name'];
        $catColors[] = "'" . $cat['color'] . "'";

        $timeLeft = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE  `deadline` < CURDATE()  
                  AND `includeInProject_id` = '$catID'
                  AND author_id = '$display' ");
        $timeLeft->execute();
        $resultLeft = $timeLeft->fetch();
        $heightCatLeft = ($resultLeft["TOTAL"] * 1);
        $arrayLeft[] = $heightCatLeft; //création d'un array pour regroupper tous les résultats
    }
    //somme de l'array
    $sumLeft = array_sum($arrayLeft);
    //100 = 100% la base du diagram pie et on enlève la taille des restes à faire dépassés
    $valueLeft = (100 - ($sumLeft));
    //implode de l'array pour un resulta: ex: 10,50,10,20 ...etc
    $left = implode($arrayLeft, ", ");

    $color = implode($catColors, ", ");
}
//----------------------------------------------------------------------------------------------------------------------

$sumLeft = array_sum($arrayLeft);

if ($sumLeft > 100){
    $display = "display: none;";
} else {
    $display = "display: block;";
?>

<div class="pie-left-php" style=" <?php echo $display ?>">
    <canvas id="myCanvas3" width="200" height="200"></canvas>
</div>

<script type="text/javascript">
    var obj3 = {
        values: [<?php echo $valueLeft ?>, <?php echo $left ?>],
        colors: ['#191919', <?php echo $color ?>],
        animation: true,
        animationSpeed: 5, // Time in miliisecond & default animation speed is 20ms
        fillTextColor: '#fff',
        fillTextPosition: 'horizontal',
        doughnutHoleSize: null,
        doughnutHoleColor: '#fff',
        offset: null
    };

    //Generate myCanvas3
    generatePieGraph('myCanvas3', obj3);
</script>

 <?php } ?>
</body>
</html>