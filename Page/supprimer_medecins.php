<?php
include('menu.php');
include('bdd.php');

// Initialiser la variable pour stocker l'identifiant du médecin à supprimer
$id_medecin = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du médecin à supprimer
    $id_medecin = $_GET["id"];

    try {
        // Requête pour supprimer le médecin
        $sql = "DELETE FROM medecins WHERE id = :id_medecin";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_medecin', $id_medecin);
        $stmt->execute();

        echo "<h3 style='color: green;'>Medecin supprimé avec succès. Vous allez être redirigé vers la page d'affichage des médecins.</h3>";
        header("refresh:3;url=affichage_medecins.php");

        

    } catch (PDOException $e) {
        echo "Erreur de suppression de médecin : " . $e->getMessage();
    }

    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Médecin</title>
</head>
<body>

    <?php if ($id_medecin != "") {
        echo "";
    } else {
        echo "Aucun médecin sélectionné.";
    } ?>


<?php
    include('footer.php');
    ?>

</body>
</html>
