    <!-- SEMAINE 1 ---------------------------------------------------------------------------------------->
    <div class="col">
        <div class="date"><?php
            $jour = date("d-m-Y");
            $d1 = strtotime($jour . "+ 7 days");
            ?>
            Du <?php echo $jour . "<br>"; ?>
            Au <?php echo date("d-m-Y", $d1) . "<br>"; ?>
        </div>
        <?php
        /**-------------------------------------------------------------------------------------------------
         * PREMIERE SEMAINE
         *
         * PARTIE: TOUS
         *
         *  le calcul de la semaine ne devras pas dépasser les 90heures, sinon la bar sera trop grande
         * donc à la place il y aura un message "d'erreur" avec le nombre d'heure de la semaine
         *
         * -----------------------------------------------------------------------------------------------*/

        //si tous le monde est séléctionné
        if ($display == 0) {
            $sumErrorsST1 = 0;
            //récupération de l'id de la catégorie de projet
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            //boucle pour afficher toutes les catégories de projets
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];

                //calcule de la somme pour tous le monde
                $sumDurationST1 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                  AND `includeInProject_id` = '$catID'");
                $sumDurationST1->execute();
                $sumDurST1 = $sumDurationST1->fetch();
                //résultat de la somme par catégorie, multiplié par 3 pour plus grande barre visuel
                $sumDurST1E = ($sumDurST1["TOTAL"] * 3);
                //calcule pour avoir la sommes de toutes les catégories
                $sumErrorsST1 = $sumDurST1E + $sumErrorsST1;
            }
            //résultat de la somme de toutes les catégories
            $sumErrorsTousST1 = $sumErrorsST1;

            // si le résultat ne dépasse pas 270px
            if ($sumErrorsTousST1 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];

                    $timeS1 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                  AND `includeInProject_id` = '$catID'");
                    $timeS1->execute();
                    $resultS1 = $timeS1->fetch();
                    $heightS1 = ($resultS1["TOTAL"] * 3);
                    /** première bar du diagram central
                     *$heightS1 = nombre d'heure d'un catégorie multiplié par 3 pour avoir un meilleur visiuel en pixel
                     */
                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS1 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } //si la taille total de la semaine un fais plus de 270 pixel alors ce message d'erreur pour remplacer la barre du diagram
            else { ?>
                <div class="barBase"
                     style="height: 0px;"></div>
                <?php
                $sumTotalST1 = ($sumErrorsTousST1 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalST1
                    . " heures"
                    . '</div>';

            }
        } /**
         *
         * --------------------------------------------------------------------------------- DEUXIEME PARTIE
         *
         * Identique à la partie une
         * sauf que le calcul est fais par l'id(auteur)
         * choisis par le select
         * et sauvegardé dans la session
         *
         */
        elseif ($display != 0) {
            $sumErrorsSA1 = 0;
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                $sumDurationSA1 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                                FROM RAF
                                WHERE `deadline`
                                BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) 
                                  AND `includeInProject_id` = '$catID' 
                                  AND author_id = '$display'");
                $sumDurationSA1->execute();
                $sumDurSA1 = $sumDurationSA1->fetch();
                $sumDurSA1E = ($sumDurSA1["TOTAL"] * 3);

                $sumErrorsSA1 = $sumDurSA1E + $sumErrorsSA1;

            }
            $sumErrorsAuthSA1 = $sumErrorsSA1;

            if ($sumErrorsAuthSA1 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];
                    $timeS1 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
    FROM RAF
    WHERE `deadline`
    BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND `includeInProject_id` = '$catID' 
      AND author_id = '$display'");
                    $timeS1->execute();
                    $resultS1 = $timeS1->fetch();
                    $heightS1 = ($resultS1["TOTAL"] * 3);

                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS1 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } else {
                $sumTotalSA1 = ($sumErrorsAuthSA1 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalSA1
                    . " heures"
                    . '</div>';
            }
        }
        ?>
        <div class="barBase" style="height: 0;"></div>

    </div>
    <!-- SEMAINE 2 ---------------------------------------------------------------------------------------->

    <div class="col">
        <div class="date"><?php
            $jour = date("d-m-Y");
            $d2 = strtotime($jour . "+ 8 days");
            $d3 = strtotime($jour . "+ 15 days");
            ?>
            Du <?php echo date("d-m-Y", $d2) . "<br>"; ?>
            Au <?php echo date("d-m-Y", $d3) . "<br>"; ?>
        </div>
        <?php
        /**-------------------------------------------------------------------------------------------------
         * DEUXIEME SEMAINE
         *
         * PARTIE: TOUS
         *
         *  le calcul de la semaine ne devras pas dépasser les 90heures, sinon la bar sera trop grande
         * donc à la place il y aura un message "d'erreur" avec le nombre d'heure de la semaine
         *
         * -----------------------------------------------------------------------------------------------*/

        //si tous le monde est séléctionné
        if ($display == 0) {
            $sumErrorsST2 = 0;
            //récupération de l'id de la catégorie de projet
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            //boucle pour afficher toutes les catégories de projets
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                //calcule de la somme pour tous le monde
                $sumDurationST2 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND DATE_ADD(CURDATE(), INTERVAL 15 DAY) 
                  AND `includeInProject_id` = '$catID'");
                $sumDurationST2->execute();
                $sumDurST2 = $sumDurationST2->fetch();
                //résultat de la somme par catégorie, multiplié par 3 pour plus grande barre visuel
                $sumDurST2E = ($sumDurST2["TOTAL"] * 3);
                //calcule pour avoir la sommes de toutes les catégories
                $sumErrorsST2 = $sumDurST2E + $sumErrorsST2;
            }
            //résultat de la somme de toutes les catégories
            $sumErrorsTousST2 = $sumErrorsST2;

            // si le résultat ne dépasse pas 270px
            if ($sumErrorsTousST2 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];

                    $timeS2 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND DATE_ADD(CURDATE(), INTERVAL 15 DAY) 
                  AND `includeInProject_id` = '$catID'");
                    $timeS2->execute();
                    $resultS2 = $timeS2->fetch();
                    $heightS2 = ($resultS2["TOTAL"] * 3);
                    /** première bar du diagram central
                     *$heightS1 = nombre d'heure d'un catégorie multiplié par 3 pour avoir un meilleur visiuel en pixel
                     */
                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS2 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } //si la taille total de la semaine un fais plus de 270 pixel alors ce message d'erreur pour remplacer la barre du diagram
            else { ?>
                <div class="barBase"
                     style="height: 0px;"></div>
                <?php
                $sumTotalST2 = ($sumErrorsTousST2 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalST2
                    . " heures"
                    . '</div>';

            }
        } /**
         *
         * --------------------------------------------------------------------------------- DEUXIEME PARTIE
         *
         * Identique à la partie une
         * sauf que le calcul est fais par l'id(auteur)
         * choisis par le select
         * et sauvegardé dans la session
         *
         */
        elseif ($display != 0) {
            $sumErrorsSA2 = 0;
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                $sumDurationSA2 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                                FROM RAF
                                WHERE `deadline`
                                BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)
                                  AND `includeInProject_id` = '$catID' 
                                  AND author_id = '$display'");
                $sumDurationSA2->execute();
                $sumDurSA2 = $sumDurationSA2->fetch();
                $sumDurSA2E = ($sumDurSA2["TOTAL"] * 3);

                $sumErrorsSA2 = $sumDurSA2E + $sumErrorsSA2;

            }
            $sumErrorsAuthSA2 = $sumErrorsSA2;

            if ($sumErrorsAuthSA2 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];
                    $timeS2 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
    FROM RAF
    WHERE `deadline`
    BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND DATE_ADD(CURDATE(), INTERVAL 15 DAY) 
      AND `includeInProject_id` = '$catID' 
      AND author_id = '$display'");
                    $timeS2->execute();
                    $resultS2 = $timeS2->fetch();
                    $heightS2 = ($resultS2["TOTAL"] * 3);

                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS2 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } else {
                $sumTotalSA2 = ($sumErrorsAuthSA2 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalSA2
                    . " heures"
                    . '</div>';
            }
        }
        ?>
        <div class="barBase" style="height: 0;"></div>
    </div>
    <!-- SEMAINE 3 ---------------------------------------------------------------------------------------->

    <div class="col">
        <div class="date"><?php
            $jour = date("d-m-Y");
            $d4 = strtotime($jour . "+ 16 days");
            $d5 = strtotime($jour . "+ 23 days");
            ?>
            Du <?php echo date("d-m-Y", $d4) . "<br>"; ?>
            Au <?php echo date("d-m-Y", $d5) . "<br>"; ?>
        </div>

        <?php
        /**-------------------------------------------------------------------------------------------------
         * TROISIEME SEMAINE
         *
         * PARTIE: TOUS
         *
         *  le calcul de la semaine ne devras pas dépasser les 90heures, sinon la bar sera trop grande
         * donc à la place il y aura un message "d'erreur" avec le nombre d'heure de la semaine
         *
         * -----------------------------------------------------------------------------------------------*/

        //si tous le monde est séléctionné
        if ($display == 0) {
            $sumErrorsST3 = 0;
            //récupération de l'id de la catégorie de projet
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            //boucle pour afficher toutes les catégories de projets
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                //calcule de la somme pour tous le monde
                $sumDurationST3 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND DATE_ADD(CURDATE(), INTERVAL 23 DAY)
                  AND `includeInProject_id` = '$catID'");
                $sumDurationST3->execute();
                $sumDurST3 = $sumDurationST3->fetch();
                //résultat de la somme par catégorie, multiplié par 3 pour plus grande barre visuel
                $sumDurST3E = ($sumDurST3["TOTAL"] * 3);
                //calcule pour avoir la sommes de toutes les catégories
                $sumErrorsST3 = $sumDurST3E + $sumErrorsST3;
            }
            //résultat de la somme de toutes les catégories
            $sumErrorsTousST3 = $sumErrorsST3;

            // si le résultat ne dépasse pas 270px
            if ($sumErrorsTousST3 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];

                    $timeS3 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND DATE_ADD(CURDATE(), INTERVAL 23 DAY)
                  AND `includeInProject_id` = '$catID'");
                    $timeS3->execute();
                    $resultS3 = $timeS3->fetch();
                    $heightS3 = ($resultS3["TOTAL"] * 3);
                    /** première bar du diagram central
                     *$heightS1 = nombre d'heure d'un catégorie multiplié par 3 pour avoir un meilleur visiuel en pixel
                     */
                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS3 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } //si la taille total de la semaine un fais plus de 270 pixel alors ce message d'erreur pour remplacer la barre du diagram
            else { ?>
                <div class="barBase"
                     style="height: 0px;"></div>
                <?php
                $sumTotalST3 = ($sumErrorsTousST3 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalST3
                    . " heures"
                    . '</div>';

            }
        } /**
         *
         * --------------------------------------------------------------------------------- DEUXIEME PARTIE
         *
         * Identique à la partie une
         * sauf que le calcul est fais par l'id(auteur)
         * choisis par le select
         * et sauvegardé dans la session
         *
         */
        elseif ($display != 0) {
            $sumErrorsSA3 = 0;
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                $sumDurationSA3 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                                FROM RAF
                                WHERE `deadline`
                                BETWEEN DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND DATE_ADD(CURDATE(), INTERVAL 23 DAY)
                                  AND `includeInProject_id` = '$catID' 
                                  AND author_id = '$display'");
                $sumDurationSA3->execute();
                $sumDurSA3 = $sumDurationSA3->fetch();
                $sumDurSA3E = ($sumDurSA3["TOTAL"] * 3);

                $sumErrorsSA3 = $sumDurSA3E + $sumErrorsSA3;

            }
            $sumErrorsAuthSA3 = $sumErrorsSA3;

            if ($sumErrorsAuthSA3 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];
                    $timeS3 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
    FROM RAF
    WHERE `deadline`
    BETWEEN DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND DATE_ADD(CURDATE(), INTERVAL 23 DAY)
      AND `includeInProject_id` = '$catID' 
      AND author_id = '$display'");
                    $timeS3->execute();
                    $resultS3 = $timeS3->fetch();
                    $heightS3 = ($resultS3["TOTAL"] * 3);

                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS3 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } else {
                $sumTotalSA3 = ($sumErrorsAuthSA3 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalSA3
                    . " heures"
                    . '</div>';
            }
        }
        ?>
        <div class="barBase" style="height:0;"></div>

    </div>
    <!-- SEMAINE 4 ---------------------------------------------------------------------------------------->

    <div class="col">
        <div class="date"><?php
            $jour = date("d-m-Y");
            $d6 = strtotime($jour . "+ 24 days");
            $d7 = strtotime($jour . "+ 31 days");
            ?>
            Du <?php echo date("d-m-Y", $d6) . "<br>"; ?>
            Au <?php echo date("d-m-Y", $d7) . "<br>"; ?>
        </div>

        <?php
        /**-------------------------------------------------------------------------------------------------
         * QUATRIEME SEMAINE
         *
         * PARTIE: TOUS
         *
         *  le calcul de la semaine ne devras pas dépasser les 90heures, sinon la bar sera trop grande
         * donc à la place il y aura un message "d'erreur" avec le nombre d'heure de la semaine
         *
         * -----------------------------------------------------------------------------------------------*/

        //si tous le monde est séléctionné
        if ($display == 0) {
            $sumErrorsST4 = 0;
            //récupération de l'id de la catégorie de projet
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            //boucle pour afficher toutes les catégories de projets
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                //calcule de la somme pour tous le monde
                $sumDurationST4 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 24 DAY) AND DATE_ADD(CURDATE(), INTERVAL 41 DAY)
                  AND `includeInProject_id` = '$catID'");
                $sumDurationST4->execute();
                $sumDurST4 = $sumDurationST4->fetch();
                //résultat de la somme par catégorie, multiplié par 4 pour plus grande barre visuel
                $sumDurST4E = ($sumDurST4["TOTAL"] * 3);
                //calcule pour avoir la sommes de toutes les catégories
                $sumErrorsST4 = $sumDurST4E + $sumErrorsST4;
            }
            //résultat de la somme de toutes les catégories
            $sumErrorsTousST4 = $sumErrorsST4;

            // si le résultat ne dépasse pas 270px
            if ($sumErrorsTousST4 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];

                    $timeS4 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 24 DAY) AND DATE_ADD(CURDATE(), INTERVAL 41 DAY)
                  AND `includeInProject_id` = '$catID'");
                    $timeS4->execute();
                    $resultS4 = $timeS4->fetch();
                    $heightS4 = ($resultS4["TOTAL"] * 3);
                    /** première bar du diagram central
                     *$heightS1 = nombre d'heure d'un catégorie multiplié par 3 pour avoir un meilleur visiuel en pixel
                     */
                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS4 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } //si la taille total de la semaine un fais plus de 270 pixel alors ce message d'erreur pour remplacer la barre du diagram
            else { ?>
                <div class="barBase"
                     style="height: 0px;"></div>
                <?php
                $sumTotalST4 = ($sumErrorsTousST4 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalST4
                    . " heures"
                    . '</div>';

            }
        } /**
         *
         * --------------------------------------------------------------------------------- DEUXIEME PARTIE
         *
         * Identique à la partie une
         * sauf que le calcul est fais par l'id(auteur)
         * choisis par le select
         * et sauvegardé dans la session
         *
         */
        elseif ($display != 0) {
            $sumErrorsSA4 = 0;
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                $sumDurationSA4 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                                FROM RAF
                                WHERE `deadline`
                                BETWEEN DATE_ADD(CURDATE(), INTERVAL 24 DAY) AND DATE_ADD(CURDATE(), INTERVAL 41 DAY)
                                  AND `includeInProject_id` = '$catID' 
                                  AND author_id = '$display'");
                $sumDurationSA4->execute();
                $sumDurSA4 = $sumDurationSA4->fetch();
                $sumDurSA4E = ($sumDurSA4["TOTAL"] * 3);

                $sumErrorsSA4 = $sumDurSA4E + $sumErrorsSA4;

            }
            $sumErrorsAuthSA4 = $sumErrorsSA4;

            if ($sumErrorsAuthSA4 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];
                    $timeS4 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
    FROM RAF
    WHERE `deadline`
    BETWEEN DATE_ADD(CURDATE(), INTERVAL 24 DAY) AND DATE_ADD(CURDATE(), INTERVAL 41 DAY)
      AND `includeInProject_id` = '$catID' 
      AND author_id = '$display'");
                    $timeS4->execute();
                    $resultS4 = $timeS4->fetch();
                    $heightS4 = ($resultS4["TOTAL"] * 3);

                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS4 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } else {
                $sumTotalSA4 = ($sumErrorsAuthSA4 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalSA4
                    . " heures"
                    . '</div>';
            }
        }
        ?>
        <div class="barBase" style="height:0;"></div>
    </div>

    <!-- SEMAINE 5 ---------------------------------------------------------------------------------------->
    <div class="col">
        <div class="date"><?php
            $jour = date("d-m-Y");
            $d8 = strtotime($jour . "+ 32 days");
            $d9 = strtotime($jour . "+ 39 days");
            ?>
            Du <?php echo date("d-m-Y", $d8) . "<br>"; ?>
            Au <?php echo date("d-m-Y", $d9) . "<br>"; ?>
        </div>


        <?php
        /**-------------------------------------------------------------------------------------------------
         * CINQUIEME SEMAINE
         *
         * PARTIE: TOUS
         *
         *  le calcul de la semaine ne devras pas dépasser les 90heures, sinon la bar sera trop grande
         * donc à la place il y aura un message "d'erreur" avec le nombre d'heure de la semaine
         *
         * -----------------------------------------------------------------------------------------------*/

        //si tous le monde est séléctionné
        if ($display == 0) {
            $sumErrorsST5 = 0;
            //récupération de l'id de la catégorie de projet
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            //boucle pour afficher toutes les catégories de projets
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                //calcule de la somme pour tous le monde
                $sumDurationST5 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
                BETWEEN DATE_ADD(CURDATE(), INTERVAL 32 DAY) AND DATE_ADD(CURDATE(), INTERVAL 39 DAY)
                  AND `includeInProject_id` = '$catID'");
                $sumDurationST5->execute();
                $sumDurST5 = $sumDurationST5->fetch();
                //résultat de la somme par catégorie, multiplié par 4 pour plus grande barre visuel
                $sumDurST5E = ($sumDurST5["TOTAL"] * 3);
                //calcule pour avoir la sommes de toutes les catégories
                $sumErrorsST5 = $sumDurST5E + $sumErrorsST5;
            }
            //résultat de la somme de toutes les catégories
            $sumErrorsTousST5 = $sumErrorsST5;

            // si le résultat ne dépasse pas 270px
            if ($sumErrorsTousST5 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];

                    $timeS5 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                FROM RAF
                WHERE `deadline`
BETWEEN DATE_ADD(CURDATE(), INTERVAL 32 DAY) AND DATE_ADD(CURDATE(), INTERVAL 39 DAY)
                  AND `includeInProject_id` = '$catID'");
                    $timeS5->execute();
                    $resultS5 = $timeS5->fetch();
                    $heightS5 = ($resultS5["TOTAL"] * 3);
                    /** première bar du diagram central
                     *$heightS1 = nombre d'heure d'un catégorie multiplié par 3 pour avoir un meilleur visiuel en pixel
                     */
                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS5 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } //si la taille total de la semaine un fais plus de 270 pixel alors ce message d'erreur pour remplacer la barre du diagram
            else { ?>
                <div class="barBase"
                     style="height: 0px;"></div>
                <?php
                $sumTotalST5 = ($sumErrorsTousST5 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalST5
                    . " heures"
                    . '</div>';

            }
        } /**
         *
         * --------------------------------------------------------------------------------- DEUXIEME PARTIE
         *
         * Identique à la partie une
         * sauf que le calcul est fais par l'id(auteur)
         * choisis par le select
         * et sauvegardé dans la session
         *
         */
        elseif ($display != 0) {
            $sumErrorsSA5 = 0;
            $reqCat = $dbh->prepare("SELECT * from includeInProjects");
            $reqCat->execute();
            while ($cat = $reqCat->fetch()) {
                $catID = $cat['id'];
                $sumDurationSA5 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
                                FROM RAF
                                WHERE `deadline`
                                BETWEEN DATE_ADD(CURDATE(), INTERVAL 32 DAY) AND DATE_ADD(CURDATE(), INTERVAL 39 DAY)
                                  AND `includeInProject_id` = '$catID' 
                                  AND author_id = '$display'");
                $sumDurationSA5->execute();
                $sumDurSA5 = $sumDurationSA5->fetch();
                $sumDurSA5E = ($sumDurSA5["TOTAL"] * 3);

                $sumErrorsSA5 = $sumDurSA5E + $sumErrorsSA5;

            }
            $sumErrorsAuthSA5 = $sumErrorsSA5;

            if ($sumErrorsAuthSA5 < 270) {
                $reqCat = $dbh->prepare("SELECT * from includeInProjects");
                $reqCat->execute();
                while ($cat = $reqCat->fetch()) {
                    $catID = $cat['id'];
                    $timeS5 = $dbh->prepare("SELECT SUM(`duration`) AS TOTAL
    FROM RAF
    WHERE `deadline`
    BETWEEN DATE_ADD(CURDATE(), INTERVAL 32 DAY) AND DATE_ADD(CURDATE(), INTERVAL 39 DAY)
      AND `includeInProject_id` = '$catID' 
      AND author_id = '$display'");
                    $timeS5->execute();
                    $resultS5 = $timeS5->fetch();
                    $heightS5 = ($resultS5["TOTAL"] * 3);

                    ?>
                    <div class="barBase"
                         style="height: <?php echo $heightS5 ?>px; background-color: <?php echo $cat['color'] ?>;"></div>
                    <?php
                }
            } else {
                $sumTotalSA5 = ($sumErrorsAuthSA5 / 3);
                echo '<div class="error-height-bar">'
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . " Oops "
                    . '<i class="fas fa-exclamation-triangle"></i>'
                    . "<br>"
                    . "<br>"
                    . "Le total des heures est de "
                    . $sumTotalSA5
                    . " heures"
                    . '</div>';
            }
        }
        ?>
        <div class="barBase" style="height: <?php echo $heightS5 ?>px; background-color: #8F00C3;"></div>
    </div>

    <div class="line0">
        <div class="graduation">0</div>
        <div class="line-graduation"></div>
    </div>

    <div class="line35">
        <div class="graduation">35</div>
        <div class="line-graduation35"></div>
    </div>

