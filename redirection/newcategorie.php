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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script>
        function redir(){
            self.location.href="../index.php"
        }
        setTimeout(redir,5000)
    </script>

        <title>Redirection</title>
</head>
<body>

<?php require '../navigation/top-left-nav.php' ?>

<div class="index_col_center">
    <h3><i class="fas fa-battery-empty"></i> La nouvelle catégorie à bien été ajouté <i class="fas fa-battery-full"></i></h3>
   <p> Vous allez être redirigé dans <span id="counter">5</span> secondes.</p>
<script type="text/javascript">
    function countdown() {
        var i = document.getElementById('counter');
        if (parseInt(i.innerHTML)<=0) {
            location.href = '../index.php';
        }
        i.innerHTML = parseInt(i.innerHTML)-1;
    }
    setInterval(function(){ countdown(); },1000);
</script>
</div>
</body>
</html>