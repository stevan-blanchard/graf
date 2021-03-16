<?php
        $displayRaf = $dbh->prepare("SELECT * FROM RAF");
        $displayRaf->execute();

        //        début de l'affichage par reste à faire
        while ($display = $displayRaf->fetch(PDO::FETCH_ASSOC)) {

        $authorNames = $dbh->prepare("SELECT * FROM author WHERE id ='" . $display['author_id'] . "'");
        $authorNames->execute();
        $authorName = $authorNames->fetch();

        $includeNames = $dbh->prepare("SELECT * FROM includeInProjects WHERE id ='" . $display['includeInProject_id'] . "'");
        $includeNames->execute();
        $includeName = $includeNames->fetch();

        //calcul jour restant avant la deadline
        $today = date("Y-n-j");
        $origin = date_create($today);
        $target = date_create($display['deadline']);
        $interval = date_diff($origin, $target);
        $dDay = $interval->format('%R%a days');

        //couleur des bordures selon la priorité
        if ($display['priority'] == 4) {
            $colorBorder = '<div class="display_raf" style="border-color: red; display: inline-table;">';
        } elseif ($display['priority'] == 3) {
            $colorBorder = '<div class="display_raf" style="border-color:  orangered ; display: inline-table">';
        } elseif ($display['priority'] == 2) {
            $colorBorder = '<div class="display_raf" style="border-color:  yellow; display: inline-table">';
        } else {
            $colorBorder = '<div class="display_raf" style="border-color:  green; display: inline-table">';
        }

        //background des raf faient à 100%
        if ($display['trois_tiers'] == 1) {
            $colorBorder = '<div class="display_raf" style="border-color: #191919 ; background-color: #191919; display: inline-table">';
        }

        echo $colorBorder; //début de la div avec la bordure de couleur par prioritée

        //récupération de l'id du reste à faire pour l'edition et la suppression du/des restes à faire.
        $rafId = $display['id'];
        ?>
        <div class="displaytitle">
            <!--            si l'utilisateur est connecté il verra les icones modifier et supprimer, sinon il ne se passera rien-->
            <?php
            if (isset($sessionID)) {
                include 'edit-delete-raf/nav_edition.php';
            } ?>

            <?php
            echo '<div class="displayName">' . $authorName["name"] . "</div></div>";

            echo '<div class="displayDuration">' . '<div class="displayBaseInfo">' . "Durée: " . '</div>' . strtr($display['duration'], ".", "h") . "</div>";

            $iColor = $includeName["color"]; ?>
            <div class="displayProject" style="color: <?php echo $iColor ?>"> <?php echo $includeName["name"] ?> </div>
<?php
            //        sous la deadline calcule temps restant
            echo '<div class="displayDescription">' . "<b>Description:</b> " . $display['description'] . "</div>";

            echo '<div class="displayObs">' . '<div class="displayBaseInfo">' . "Observation: " . '</div>' . $display['observation'] . "</div>";

            //affichage d'une barre d'avancement en 3 étapes 33%, 66% et 100%
            if ($display['un_tiers'] == 0 and $display['deux_tiers'] == 0) {
                ?>
                <div class="bg_third"></div>
                <div class="bg0text">0%</div>

                <?php
            }
            if ($display['un_tiers'] == 1 and $display['deux_tiers'] == 0) {
                ?>
                <div class="bg_third"></div>
                <div class="bg33text">33%</div>
                <?php
            }
            if ($display['un_tiers'] == 1 and $display['deux_tiers'] == 1 and $display['trois_tiers'] == 0) {
                ?>
                <div class="bg_third"></div>
                <div class="bg66text">66%</div>
                <?php
            }
            if ($display['un_tiers'] == 1 and $display['deux_tiers'] == 1 and $display['trois_tiers'] == 1) {
                ?>
                <div class="bg_third"></div>
                <div class="bg100text"></div>
                <?php
            }
            echo '<div class="displayDeadline">' . $display['deadline'] . "</div>";

            //            changement de couleur quand la date est dépassé et en négatif et que celle si n'est pas terminer
            if ($dDay <= 0) {
                $a = "color:red;";
            } else {
                $a = "color:white;";
            }

            if($display['trois_tiers'] == 1){
                $a = "color:gold;";
                $dDay = $dDay." terminé";
            }

            echo '<div class="displayDDay" style="' . $a . '">' . $dDay . "</div>";

            echo "</div>";
            }
            ?>