<?php

include('menu.php');
include('bdd.php');
    

// Initialiser les variables pour stocker les valeurs du formulaire
$id_medecin = $civilite = $nom = $prenom = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du médecin à modifier
    $id_medecin = $_GET["id"];

    try {
        // Requête pour récupérer les informations du médecin à modifier
        $sql = "SELECT * FROM medecins WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_medecin);
        $stmt->execute();

        // Récupérer les données du médecin
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $civilite = $row["civilite"];
        $nom = $row["nom"];
        $prenom = $row["prenom"];

    } catch (PDOException $e) {
        echo "Erreur de récupération des informations de médecin : " . $e->getMessage();
    }

    // Fermer la connexion (PDO se déconnecte automatiquement à la fin du script)
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_medecin = $_POST["id_medecin"];
    $civilite = $_POST["civilite"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    try {
        // Requête pour mettre à jour les informations du médecin
        $sql = "UPDATE medecins SET
                civilite = :civilite,
                nom = :nom,
                prenom = :prenom
                WHERE id = :id_medecin";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':civilite', $civilite);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':id_medecin', $id_medecin);

        $stmt->execute();

        echo "<h3 style='color: green;'>Médecin modifié avec succès. Vous allez être redirigé vers la page d'affichage des médecins.</h3>";
        header("refresh:3;url=affichage_medecins.php");

    } catch (PDOException $e) {
        echo "Erreur de modification de médecin : " . $e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Médecin</title>
    <link rel="stylesheet" href="../Css/modifier_medecins.css">
</head>
<body>

    <h2>Modifier un Médecin</h2>

    <?php if ($id_medecin != "") { ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="id_medecin" value="<?php echo $id_medecin; ?>">

            <label for="civilite">Civilité:</label>
            <select name="civilite" required>
                <option value="M." <?php if ($civilite == "M.") echo "selected"; ?>>M.</option>
                <option value="Mme" <?php if ($civilite == "Mme") echo "selected"; ?>>Mme</option>
            </select><br>

            <label for="nom">Nom:</label>
            <input type="text" name="nom" value="<?php echo $nom; ?>" required><br>

            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" value="<?php echo $prenom; ?>" required><br>

            <input type="submit" value="Modifier Médecin">
        </form>
    <?php } else {
        echo "Aucun médecin sélectionné.";
    } ?>

<?php
    include('footer.php');
    ?>

</body>
</html>
