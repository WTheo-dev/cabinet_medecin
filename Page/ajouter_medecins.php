<?php
include('menu.php');
include('bdd.php');

// Initialiser les variables pour stocker les valeurs du formulaire
$civilite = $nom = $prenom = "";

// Traitement du formulaire d'ajout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $civilite = $_POST["civilite"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    try {
        // Requête pour ajouter un nouveau médecin
        $sql = "INSERT INTO medecins (civilite, nom, prenom) VALUES (:civilite, :nom, :prenom)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':civilite', $civilite);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);

        $stmt->execute();

        echo "<h3 style='color: green;'>Médecin ajouté avec succès. Vous allez être redirigé vers la page d'affichage des médecins.</h3>";
        header("refresh:3;url=affichage_medecins.php");

    } catch (PDOException $e) {
        echo "Erreur d'ajout de médecin : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Médecin</title>
    <link rel="stylesheet" href="../Css/ajouter_medecins.css">
</head>
<body>

    <h2>Ajouter un Médecin</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="civilite">Civilité:</label>
        <select name="civilite" required>
            <option value="M.">M.</option>
            <option value="Mme">Mme</option>
        </select><br>

        <label for="nom">Nom:</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required><br>

        <input type="submit" value="Ajouter Médecin">
    </form>

    <?php
        include('footer.php');
    ?>

</body>
</html>
