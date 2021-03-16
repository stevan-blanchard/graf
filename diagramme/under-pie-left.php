<div class="cat_under_pie">
    <?php if ($display == 0) {

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
            if ($heightCatLeft != 0) {
                echo '<ul class="no-padding">'
                    . ' <li class="cat_under_pie_color" style="background-color:'
                    . $cat['color']
                    . '">'
                    . '</li>'
                    . $cat['name']
                    . ': '
                    . "$heightCatLeft"
                    . " heures"
                    . '</ul>';
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
            $catColors[] = "'" . $cat['color'] . "'";

            $timeLeft = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
        FROM RAF
        WHERE  `deadline` < CURDATE()
        AND `includeInProject_id` = '$catID'
        AND author_id = '$display' ");
            $timeLeft->execute();
            $resultLeft = $timeLeft->fetch();
            $heightCatLeft = ($resultLeft["TOTAL"] * 1);
            if ($heightCatLeft != 0) {
                echo '<ul class="no-padding">'
                    . ' <li class="cat_under_pie_color" style="background-color:'
                    . $cat['color']
                    . '">'
                    . '</li>'
                    . $cat['name']
                    . ': '
                    . "$heightCatLeft"
                    . " heures"
                    . '</ul>';
            } else {
                $heightCatLeft = NULL;
            }

        }
    }

    ?>
</div>