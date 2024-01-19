<?php
include('menu.php');
include('bdd.php');

try {
    // Requête pour récupérer la liste des patients avec l'indication du médecin référent
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
    <title>Liste des Patiens</title>
    <link rel="stylesheet" href="../Css/affichage_usagers.css">
</head>
<body>

    <h2>Liste des Patients</h2>

    <a href="ajouter_usagers.php"><button>Ajouter un Patient</button></a>

    <?php
    // Vérifier si des patients sont présents
    if ($result) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Civilité</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Date de Naissance</th><th>Lieu de Naissance</th><th>Médecin Référent</th><th>Modifier</th><th>Supprimer</th></tr>";

        // Afficher les données des patients
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
        echo "Aucun patient trouvé.";
    }
    ?>

    <?php
        include('footer.php');
    ?>

</body>
</html>
