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

// Initialiser la variable pour stocker l'identifiant de l'usager à supprimer
$id_usager = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant de l'usager à supprimer
    $id_usager = $_GET["id"];

    try {
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO à exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprimer les rendez-vous associés
        $sql_delete_rdv = "DELETE FROM rendez_vous WHERE id_usager = :id_usager";
        $stmt_delete_rdv = $conn->prepare($sql_delete_rdv);
        $stmt_delete_rdv->bindParam(':id_usager', $id_usager);
        $stmt_delete_rdv->execute();

        // Ensuite, supprimer l'usager
        $sql_delete_usager = "DELETE FROM usagers WHERE id = :id_usager";
        $stmt_delete_usager = $conn->prepare($sql_delete_usager);
        $stmt_delete_usager->bindParam(':id_usager', $id_usager);
        $stmt_delete_usager->execute();

        echo "<h3 style='color: green;'>Patient supprimé avec succès. Vous allez être redirigé vers la page d'affichage des patients.</h3>";
        header("refresh:3;url=affichage_usagers.php");

    } catch (PDOException $e) {
        echo "Erreur de suppression d'usager : " . $e->getMessage();
    }

    // Fermer la connexion (PDO se déconnecte automatiquement à la fin du script)
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Usager</title>
</head>
<body>

    <?php if ($id_usager != "") {
        echo "";
    } else {
        echo "Aucun usager sélectionné.";
    } ?>

</body>
</html>
