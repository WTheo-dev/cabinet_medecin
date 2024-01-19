<?php
include('menu.php');
include('bdd.php');

try {
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
    <link rel="stylesheet" href="../Css/affichage_medecins.css">
</head>
<body>

    <h2>Liste des Médecins</h2>

   
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

    <?php
        include('footer.php');
    ?>

</body>
</html>
