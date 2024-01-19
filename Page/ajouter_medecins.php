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

// Initialiser les variables pour stocker les valeurs du formulaire
$civilite = $nom = $prenom = "";

// Traitement du formulaire d'ajout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $civilite = $_POST["civilite"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];

    try {
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO à exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Fermer la connexion (PDO se déconnecte automatiquement à la fin du script)
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Médecin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            padding: 20px;
            text-align: center;
        }

        form {
            width: 60%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

    </style>
</head>
<body>

    <h2>Ajouter un Médecin</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="civilite">Civilité:</label>
        <select name="civilite" required>
            <option value="M.">M.</option>
            <option value="Mme">Mme</option>
            <!-- Ajouter d'autres options si nécessaire -->
        </select><br>

        <label for="nom">Nom:</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required><br>

        <input type="submit" value="Ajouter Médecin">
    </form>

</body>
</html>
