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

    // Requête pour récupérer la liste des usagers avec l'indication du médecin référent
    $sql = "SELECT usagers.*, medecins.nom AS nom_medecin, medecins.prenom AS prenom_medecin
            FROM usagers
            LEFT JOIN medecins ON usagers.id_medecin_referent = medecins.id";

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
    <title>Liste des Usagers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            margin-bottom: 300px; /* Ajout de la marge en bas pour éviter de cacher le footer */
        }

        h2 {
            text-align: center;
            padding: 20px;
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

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            margin-bottom: 200px; /* Ajout de la marge en bas pour éviter de cacher le footer */
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

    </style>
</head>
<body>

    <h2>Liste des Patients</h2>

    <a href="ajouter_usagers.php"><button>Ajouter un Usager</button></a>

    <?php
    // Vérifier si des usagers sont présents
    if ($result) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Date de Naissance</th><th>Lieu de Naissance</th><th>Médecin Référent</th><th>Modifier</th><th>Supprimer</th></tr>";

        // Afficher les données des usagers
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["civilite"]."</td>";
            echo "<td>".$row["nom"]."</td>";
            echo "<td>".$row["prenom"]."</td>";
            echo "<td>".$row["adresse"]."</td>";
            echo "<td>".strftime('%d/%m/%Y', strtotime($row["date_naissance"]))."</td>"; // Afficher la date en format jj/mm/aaaa
            echo "<td>".$row["lieu_naissance"]."</td>";
            echo "<td>".($row["nom_medecin"] ? $row["prenom_medecin"]." ".$row["nom_medecin"] : "Aucun")."</td>"; // Afficher le nom du médecin référent ou "Aucun"
            echo "<td><a href='modifier_usagers.php?id=".$row["id"]."'>Modifier</a></td>";
            echo "<td><a href='supprimer_usagers.php?id=".$row["id"]."'>Supprimer</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucun usager trouvé.";
    }
    ?>

</body>
</html>
