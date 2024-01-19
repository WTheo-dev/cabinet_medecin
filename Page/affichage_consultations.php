<?php
include('menu.php');
include('bdd.php');

try {
    // Requête pour récupérer la liste des médecins
    $sql_medecins = "SELECT id, nom, prenom FROM medecins";
    $result_medecins = $conn->query($sql_medecins);

    // Initialisation du filtre du médecin
    $filter_medecin = "";

    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter_medecin'])) {
        $filter_medecin = $_POST['filter_medecin'];
    }

    // Requête pour récupérer la liste des consultations avec ou sans filtre de médecin
    $sql = "SELECT rendez_vous.id, usagers.nom AS nom_usager, usagers.prenom AS prenom_usager, 
            medecins.nom AS nom_medecin, medecins.prenom AS prenom_medecin, 
            date_consultation, heure_consultation, duree_consultation
            FROM rendez_vous
            INNER JOIN usagers ON rendez_vous.id_usager = usagers.id
            INNER JOIN medecins ON rendez_vous.id_medecin = medecins.id";

    // Ajout du filtre de médecin si un médecin est sélectionné
    if (!empty($filter_medecin)) {
        $sql .= " WHERE medecins.id = :filter_medecin";
    }

    // Ajout de l'ordre de tri
    $sql .= " ORDER BY date_consultation DESC, heure_consultation DESC";

    // Préparation de la requête
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres du filtre du médecin
    if (!empty($filter_medecin)) {
        $stmt->bindParam(':filter_medecin', $filter_medecin, PDO::PARAM_INT);
    }

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des Consultations</title>
    <link rel="stylesheet" href="../Css/affichage_consultations.css">
</head>
<body>

<h2>Affichage des Consultations</h2>

<a href="saisir_consultations.php"><button>Saisir une nouvelle consultation</button></a>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="filter_medecin">Filtrer par Médecin:</label>
    <select name="filter_medecin" onchange="this.form.submit()">
        <option value="">Tous les médecins</option>
        <?php
        // Afficher la liste des médecins dans le menu déroulant
        while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
            $selected = ($filter_medecin == $row_medecin['id']) ? 'selected' : '';
            echo "<option value='{$row_medecin["id"]}' $selected>{$row_medecin["prenom"]} {$row_medecin["nom"]}</option>";
        }
        ?>
    </select>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nom Patient</th>
        <th>Prénom Patient</th>
        <th>Nom du Médecin</th>
        <th>Date Consultation</th>
        <th>Heure Consultation</th>
        <th>Durée Consultation</th>
        <th>Modifier</th>
        <th>Supprimer</th>
    </tr>
    <?php
    // Afficher les résultats de la requête en fonction du filtre du médecin
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nom_usager']}</td>";
        echo "<td>{$row['prenom_usager']}</td>";
        echo "<td>{$row['prenom_medecin']} {$row['nom_medecin']}</td>";
        echo "<td>" . strftime('%d/%m/%Y', strtotime($row['date_consultation'])) . "</td>";
        echo "<td>{$row['heure_consultation']}</td>";
        echo "<td>{$row['duree_consultation']}</td>";
        echo "<td><a href='modifier_consultations.php?id=".$row["id"]."'>Modifier</a></td>";
        echo "<td><a href='supprimer_consultations.php?id=".$row["id"]."'>Supprimer</a></td>";
        echo "</tr>";
    }
    ?>
</table>

    <?php
        include('footer.php');
    ?>


</body>
</html>
