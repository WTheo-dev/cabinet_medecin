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

// Initialisation des variables
$repartition_usagers = array("moins25" => array("hommes" => 0, "femmes" => 0),
                             "entre25et50" => array("hommes" => 0, "femmes" => 0),
                             "plus50" => array("hommes" => 0, "femmes" => 0));
$duree_consultations = array();

try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO à exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération de la répartition des usagers selon leur âge
    $sql_repartition_usagers = "SELECT 
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 25 AND civilite = 'M.' THEN 1 ELSE 0 END) AS moins25_hommes,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 25 AND civilite = 'Mme' THEN 1 ELSE 0 END) AS moins25_femmes,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 25 AND 50 AND civilite = 'M.' THEN 1 ELSE 0 END) AS entre25et50_hommes,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 25 AND 50 AND civilite = 'Mme' THEN 1 ELSE 0 END) AS entre25et50_femmes,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) > 50 AND civilite = 'M.' THEN 1 ELSE 0 END) AS plus50_hommes,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) > 50 AND civilite = 'Mme' THEN 1 ELSE 0 END) AS plus50_femmes
        FROM usagers";

    $result_repartition_usagers = $conn->query($sql_repartition_usagers);
    $row_repartition_usagers = $result_repartition_usagers->fetch(PDO::FETCH_ASSOC);

    // Récupération de la durée totale des consultations par médecin avec le nom du médecin
    $sql_duree_consultations = "SELECT medecins.nom AS nom_medecin, COALESCE(SUM(rendez_vous.duree_consultation), 0) AS total_duree
    FROM medecins
    LEFT JOIN rendez_vous ON medecins.id = rendez_vous.id_medecin
    GROUP BY medecins.nom";

    $result_duree_consultations = $conn->query($sql_duree_consultations);
    while ($row_duree_consultations = $result_duree_consultations->fetch(PDO::FETCH_ASSOC)) {
        $duree_consultations[$row_duree_consultations["nom_medecin"]] = $row_duree_consultations["total_duree"];
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            padding: 20px;
        }

        h2 {
            margin-top: 30px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .tab2{
            margin-bottom: 300px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Statistiques</h1>

    <?php
    // Affichage de la répartition des usagers selon leur âge
    echo "<h2>Répartition des patients selon leur âge et sexe</h2>";
    echo "<table class='tab1' border='1'>";
    echo "<tr><th>Tranche d'âge</th><th>Nb Hommes</th><th>Nb Femmes</th></tr>";
    echo "<tr><td>Moins de 25 ans</td><td>{$row_repartition_usagers['moins25_hommes']}</td><td>{$row_repartition_usagers['moins25_femmes']}</td></tr>";
    echo "<tr><td>Entre 25 et 50 ans</td><td>{$row_repartition_usagers['entre25et50_hommes']}</td><td>{$row_repartition_usagers['entre25et50_femmes']}</td></tr>";
    echo "<tr><td>Plus de 50 ans</td><td>{$row_repartition_usagers['plus50_hommes']}</td><td>{$row_repartition_usagers['plus50_femmes']}</td></tr>";
    echo "</table>";

    // Affichage de la durée totale des consultations par médecin
    echo "<h2>Durée totale des consultations par médecin</h2>";
    echo "<table class='tab2' border='1'>";
    echo "<tr><th>Médecin</th><th>Durée totale (heures)</th></tr>";
    // Boucle pour afficher chaque médecin et sa durée totale
    foreach ($duree_consultations as $nom_medecin => $total_duree) {
        echo "<tr><td>$nom_medecin</td><td>$total_duree</td></tr>";
    }
    echo "</table>";
    ?>

</body>
</html>
