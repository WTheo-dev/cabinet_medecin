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

// Initialiser la variable pour stocker l'identifiant du médecin à supprimer
$id_medecin = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du médecin à supprimer
    $id_medecin = $_GET["id"];

    try {
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO à exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Fermer la connexion (PDO se déconnecte automatiquement à la fin du script)
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

</body>
</html>
