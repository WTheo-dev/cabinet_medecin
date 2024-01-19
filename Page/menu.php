<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet Médical</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            padding: 10px;
            text-align: center;
            display: flex;
            align-items: center; /* Aligner verticalement les éléments du header */
            justify-content: center; 
        }

        header img {
            margin-right: 10px; /* Ajouter une marge à droite de l'image pour l'espace */
            width: 100px;
        }

        nav {
            background-color: #333;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        nav a:last-child {
            margin-left: auto;
        }

        .footer {
            position: fixed;
            background-color: #007bff;
            padding: 10px;
            text-align: center;
            bottom: 0;
            width: 100%;
            margin-top: 500px; /* Ajout de la marge en bas pour éviter de cacher le footer */
        }
    </style>
</head>
<body>

    <header>
        <img src="../images/icon_medecin.png" alt="icon medecin">
        <h1>Cabinet Médical</h1>
    </header>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="affichage_usagers.php">Patients</a>
        <a href="affichage_medecins.php">Médecins</a>
        <a href="affichage_consultations.php">Consultations</a>
        <a href="statistiques.php">Statistiques</a>
        <a href="deconnexion.php">Se déconnecter</a>
    </nav>

    <footer class="footer">
        <p>&copy; 2024 Santé ! Mais pas des pieds.... Tous droits réservés.</p>
    </footer>

    
</body>
</html>
