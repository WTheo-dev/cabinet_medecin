<?php
include('menu.php');
include('bdd.php');

// Initialiser la variable pour stocker l'identifiant du patient à supprimer
$id_usager = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du patient à supprimer
    $id_usager = $_GET["id"];

    try {
        // Supprimer les rendez-vous associés
        $sql_delete_rdv = "DELETE FROM rendez_vous WHERE id_usager = :id_usager";
        $stmt_delete_rdv = $conn->prepare($sql_delete_rdv);
        $stmt_delete_rdv->bindParam(':id_usager', $id_usager);
        $stmt_delete_rdv->execute();

        // Ensuite, supprimer le patient
        $sql_delete_usager = "DELETE FROM usagers WHERE id = :id_usager";
        $stmt_delete_usager = $conn->prepare($sql_delete_usager);
        $stmt_delete_usager->bindParam(':id_usager', $id_usager);
        $stmt_delete_usager->execute();

        echo "<h3 style='color: green;'>Patient supprimé avec succès. Vous allez être redirigé vers la page d'affichage des patients.</h3>";
        header("refresh:3;url=affichage_usagers.php");

    } catch (PDOException $e) {
        echo "Erreur de suppression du patient : " . $e->getMessage();
    }

    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Patient</title>
</head>
<body>

    <?php if ($id_usager != "") {
        echo "";
    } else {
        echo "Aucun patient sélectionné.";
    } ?>

<?php
    include('footer.php');
    ?>

</body>
</html>
