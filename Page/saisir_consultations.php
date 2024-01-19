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
$id_usager = $id_medecin = $date_consultation = $heure_consultation = $duree_consultation = "";
$error_message = "";

try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO à exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer la liste des usagers
    $sql_usagers = "SELECT id, nom, prenom, id_medecin_referent FROM usagers";
    $result_usagers = $conn->query($sql_usagers);

    $sql_medecins = "SELECT id, nom, prenom FROM medecins";
    $result_medecins = $conn->query($sql_medecins);

    // Vérification avant l'ajout d'un nouveau rendez-vous
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usager = $_POST["id_usager"];
        $id_medecin = $_POST["id_medecin"];
        $date_consultation = $_POST["date_consultation"];
        $heure_consultation = $_POST["heure_consultation"];
        $duree_consultation = $_POST["duree_consultation"]; // Ajout de la récupération de la durée

        // Vérifier si un rendez-vous existe déjà pour ce médecin ou cet usager à la même date et heure
        $sql_check_duplicate = "SELECT COUNT(*) as count_duplicates FROM rendez_vous
                                WHERE (id_usager = :id_usager OR id_medecin = :id_medecin)
                                AND date_consultation = :date_consultation
                                AND heure_consultation = :heure_consultation";

        $stmt_check_duplicate = $conn->prepare($sql_check_duplicate);
        $stmt_check_duplicate->bindParam(':id_usager', $id_usager, PDO::PARAM_INT);
        $stmt_check_duplicate->bindParam(':id_medecin', $id_medecin, PDO::PARAM_INT);
        $stmt_check_duplicate->bindParam(':date_consultation', $date_consultation, PDO::PARAM_STR);
        $stmt_check_duplicate->bindParam(':heure_consultation', $heure_consultation, PDO::PARAM_STR);

        $stmt_check_duplicate->execute();
        $result_check_duplicate = $stmt_check_duplicate->fetch(PDO::FETCH_ASSOC);

        // Si un rendez-vous existe déjà, afficher un message d'erreur
        if ($result_check_duplicate['count_duplicates'] > 0) {
            $error_message = "Un rendez-vous existe déjà pour ce médecin ou cet usager à la même date et heure.";
        } else {
            // Insertion du nouveau rendez-vous
            // ... (code d'insertion du rendez-vous dans la base de données)
            // Connexion à la base de données avec PDO
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Définir le mode d'erreur PDO à exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour enregistrer la consultation
            $sql = "INSERT INTO rendez_vous (id_usager, id_medecin, date_consultation, heure_consultation, duree_consultation) 
                    VALUES (:id_usager, :id_medecin, :date_consultation, :heure_consultation, :duree_consultation)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_usager', $id_usager);
            $stmt->bindParam(':id_medecin', $id_medecin);
            $stmt->bindParam(':date_consultation', $date_consultation);
            $stmt->bindParam(':heure_consultation', $heure_consultation);
            $stmt->bindParam(':duree_consultation', $duree_consultation);

            $stmt->execute();

            echo "<h3 style='color: green;'>Consultation ajoutée avec succès. Vous allez être redirigé vers la page d'affichage des consultations.</h3>";
            header("refresh:3;url=affichage_consultations.php");
        }
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
    <title>Saisir une Consultation</title>
    <style>
        .error {
            color: red;
        }

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
            width: 50%;
            margin: 10px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-bottom: 300px;
        }

        label, select, input {
            display: block;
            margin: 10px 0;
        }

        select, input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Saisir une Consultation</h2>

    <?php
    // Afficher le message d'erreur s'il existe
    if (!empty($error_message)) {
        echo '<p class="error">' . $error_message . '</p>';
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_usager">Usager:</label>
        <select name="id_usager" required onchange="updateMedecin()">
            <option value="">Sélectionner un usager</option>
            <?php
            // Afficher la liste des usagers
            while ($row_usager = $result_usagers->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='".$row_usager["id"]."'>".$row_usager["nom"]." ".$row_usager["prenom"]."</option>";
            }
            ?>
        </select>

        <label for="id_medecin">Médecin:</label>
        <select name="id_medecin" required>
            <option value="">Sélectionner un médecin</option>
            <?php
            // Afficher la liste des médecins
            while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='".$row_medecin["id"]."'>".$row_medecin["nom"]." ".$row_medecin["prenom"]."</option>";
            }
            ?>
        </select>

        <!-- Utiliser la date et l'heure actuelles par défaut -->
        <label for="date_consultation">Date de Consultation:</label>
        <input type="date" name="date_consultation" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="heure_consultation">Heure de Consultation:</label>
        <input type="time" name="heure_consultation" value="<?php echo date('H:i'); ?>" required>

        <label for="duree_consultation">Durée (en minutes):</label>
        <input type="number" name="duree_consultation" value="30" required>

        <input type="submit" value="Enregistrer Consultation">
    </form>

    <script>
        function updateMedecin() {
            // ... (code JavaScript pour mettre à jour le médecin par défaut en fonction de l'usager sélectionné)
        }
    </script>

</body>
</html>
