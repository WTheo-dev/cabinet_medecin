<?php
include('menu.php');
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['utilisateur_authentifie']) || $_SESSION['utilisateur_authentifie'] !== true) {
    // Rediriger vers la page de connexion s'il n'est pas authentifié
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Santé ! Mais pas des pieds..</title>
    <style>
        h1 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        .index {
            padding: 80px; /* Adjust the padding as needed */
        }

        .text {
            text-align: center;
            
        }
    </style>
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
    
</body>
</html>
