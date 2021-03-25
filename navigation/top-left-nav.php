<div class="index-top-nav">
    <h1><a href="../index.php">Projet GRAF v1.7</a></h1>
    <div class="connexion">
        <?php
        if (isset($sessionID)) {
            echo '<a href="../index.php?individual=oui" class="co1">' . $_SESSION['authorName'] . '</a>';
            echo '<a href="../membres/logout.php" class="co2">Se déconnecter</a>';
            //TODO afficher les cartes
        } else {
            echo '<a href="../membres/form-inscription.php" class="co1">Nouveau membre</a>';
            echo '<a href="../membres/login.php" class="co2">Connexion</a>';
        } ?>
    </div>
</div>
<div class="index_nav_col_left">
    <?php
    if (isset($sessionID)) {
        echo '<ul class="left_nav_top_ul">';
        echo '<li><a href="../form-RAF/form-raf.php">Nouveau reste à faire</a></li>';
        echo '<li><a href="../includeInProject/includeInProject.php">Nouvelle catégorie de projet</a></li>';
        echo '</ul>';

        echo '<ul class="left_nav_top_ul">';
        echo '<li><a href="../managment/req-author.php">Manage auteurs</a></li>';
        echo '<li><a href="../managment/req-includeInProj.php">Manage catégories projet</a></li>';
    } else{
    ?>
    <ul class="left_nav_top_ulOFF">
        <li>Veuillez-vous connecter pour voir les options</li>
        <?php } ?>
    </ul>

    <ul class="left_nav_top_ul">
        <li><a href="../diagramme/diagramme-time-left.php">Diagramme des RAF</a></li>
        <li><a href="../historique/hist-modif.php">Historique de modification</a></li>
    </ul>

    <ul class='left_nav_top_ulOFF'>
        <li><a href="../Licence_CeCILL_V2.1-fr.html">Protègé par la licence CeCILL_v2.1fr</a></li>
    </ul>
</div>