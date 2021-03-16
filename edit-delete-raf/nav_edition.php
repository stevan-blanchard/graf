<!--Les deux icons sur les petits cartes RAF, modification et suppression du reste à faire.
 avec script pour une confirmation de la suppression -->
<div class="displayEdit">
    <a href="/edit-delete-raf/edit-raf.php?id=<?php echo $rafId; ?>" title="Modifier?"><i class="fas fa-edit"></i></a>
    <a class="confirmModal" href="/edit-delete-raf/delete.php?id=<?php echo $rafId; ?>" title="Supprimer?"><i
            class="fas fa-trash-alt"></i></a>
    <!--script pour confirmer ou non la suppression d'un reste à faire-->
    <script>
        $(document).ready(function () {

            $("#dialog-confirm").dialog({
                resizable: false,
                height: 160,
                width: 500,
                autoOpen: false,
                modal: true,
                buttons: {
                    "Oui": function () {
                        $(this).dialog("close");
                        window.location.href = theHREF;
                    },
                    "Annuler": function () {
                        $(this).dialog("close");
                    }
                }
            });

            $("a.confirmModal").click(function (e) {
                e.preventDefault();
                theHREF = $(this).attr("href");
                $("#dialog-confirm").dialog("open");
            });
        });

    </script>
    <div id="dialog-confirm" title="Confirmation de la suppression" style="display:none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
            Etes-vous sûr de vouloir supprimer cet élément ?
        </p>
    </div>
</div>