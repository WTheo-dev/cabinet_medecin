<?php

session_start();
// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['utilisateur_authentifie']) || $_SESSION['utilisateur_authentifie'] !== true) {
    // Rediriger vers la page de connexion s'il n'est pas authentifié
    header("Location: login.php");
    exit();
}

include('menu.php');
    
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cabinet_medical";

// Initialiser les variables pour stocker les valeurs du formulaire
$id_medecin = $civilite = $nom = $prenom = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du médecin à modifier
    $id_medecin = $_GET["id"];

    try {
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO à exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur PDO à exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Fermer la connexion (PDO se déconnecte automatiquement à la fin du script)
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Médecin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        form {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
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
                <!-- Ajouter d'autres options si nécessaire -->
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

</body>
</html>
