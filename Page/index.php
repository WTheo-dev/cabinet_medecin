<?php
include('menu.php');
include('bdd.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Santé ! Mais pas des pieds..</title>
    <link rel="stylesheet" href="../Css/index.css">
</head>
<body>

    <h1>Santé ! Mais pas des pieds... </h1>

    <div class="container">
        <div>
            <img class="index" src="../images/calendrier.png" alt="icon">
            <div class="text">
                <p><strong>Prenez rendez vous !</strong></p>
            </div>
        </div>
        
        <div>
            <img class="index" src="../images/coeur.png" alt="icon">
            <div class="text">
            <p><strong>Faites vous soigner !</strong></p>
            </div>
        </div>
        
        <div>
            <img class="index" src="../images/megaphone.png" alt="icon">
            <div class="text">
            <p><strong>Parlez en autour de vous !</strong></p>
            </div>
        </div>
    </div>

    <?php
        include('footer.php');
    ?>
    
</body>
</html>
