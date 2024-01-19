<?php
include('menu.php');
include('bdd.php');

// Initialiser la variable pour stocker l'ID du rendez-vous
$id = "";

try {
    // Vérification si l'ID du rendez-vous est passé en paramètre
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        // Requête pour supprimer le rendez-vous de la base de données
        $sql = "DELETE FROM rendez_vous WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Message de succès
        $success_message = "Le rendez-vous a été supprimé avec succès.";
    } else {
        echo "ID du rendez-vous non spécifié.";
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
    <title>Suppression de Rendez-vous</title>
</head>
<body>

    <h2>Suppression de Rendez-vous</h2>

    <?php
    if (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    ?>

    <p>Vous allez être redirigé vers la page d'affichage des consultations.</p>
    <meta http-equiv="refresh" content="3;url=affichage_consultations.php">

    <?php
    include('footer.php');
    ?>

</body>
</html>
