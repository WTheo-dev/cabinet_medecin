<?php
include('menu.php');
session_start();
// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['utilisateur_authentifie']) || $_SESSION['utilisateur_authentifie'] !== true) {
    // Rediriger vers la page de connexion s'il n'est pas authentifié
    header("Location: login.php");
    exit();
}

// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cabinet_medical";

try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO à exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les médecins
    $sql = "SELECT * FROM medecins";
    $result = $conn->query($sql);

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Médecins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            padding: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            margin-bottom: 300px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: white;
        }


        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Liste des Médecins</h2>

    <!-- Ajouter le bouton pour ajouter un nouveau médecin -->
    <a href="ajouter_medecins.php"><button>Ajouter un Médecin</button></a>

    <?php
    // Vérifier si des médecins sont présents
    if ($result->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Civilité</th><th>Prénom</th><th>Nom</th><th>Modifier</th><th>Supprimer</th></tr>";

        // Afficher les données des médecins
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["civilite"]."</td>";
            echo "<td>".$row["prenom"]."</td>";
            echo "<td>".$row["nom"]."</td>";
            echo "<td><a href='modifier_medecins.php?id=".$row["id"]."'>Modifier</a></td>";
            echo "<td><a href='supprimer_medecins.php?id=".$row["id"]."'>Supprimer</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucun médecin trouvé.";
    }
    ?>

</body>
</html>
