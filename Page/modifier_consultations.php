<?php
include('menu.php');
include('bdd.php');



// Initialiser les variables pour stocker les valeurs du formulaire
$id = $date_consultation = $heure_consultation = $duree_consultation = $id_medecin = "";
$error_message = "";

try {
    
    // Requête pour récupérer la liste des médecins
    $sql_medecins = "SELECT id, nom, prenom FROM medecins";
    $result_medecins = $conn->query($sql_medecins);

    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $id = $_POST["id"];
        $date_consultation = $_POST["date_consultation"];
        $heure_consultation = $_POST["heure_consultation"];
        $duree_consultation = $_POST["duree_consultation"];
        $id_medecin = $_POST["id_medecin"];

        // Validation (ajoutez votre propre logique de validation si nécessaire)

        // Vérification s'il existe un autre rendez-vous pour la même date, heure et médecin (sauf le rendez-vous actuel)
        $sql_check_conflict = "SELECT id FROM rendez_vous WHERE
                               date_consultation = :date_consultation AND
                               heure_consultation = :heure_consultation AND
                               id_medecin = :id_medecin AND
                               id <> :id";
        $stmt_check_conflict = $conn->prepare($sql_check_conflict);
        $stmt_check_conflict->bindParam(':date_consultation', $date_consultation);
        $stmt_check_conflict->bindParam(':heure_consultation', $heure_consultation);
        $stmt_check_conflict->bindParam(':id_medecin', $id_medecin, PDO::PARAM_INT);
        $stmt_check_conflict->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check_conflict->execute();

        if ($stmt_check_conflict->rowCount() > 0) {
            $error_message = "Impossible de modifier le rendez-vous. Un autre rendez-vous existe pour la même date, heure et médecin.";
        } else {
            // Mise à jour des données dans la base de données
            $sql_update = "UPDATE rendez_vous SET 
                            date_consultation = :date_consultation, 
                            heure_consultation = :heure_consultation, 
                            duree_consultation = :duree_consultation,
                            id_medecin = :id_medecin 
                            WHERE id = :id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':date_consultation', $date_consultation);
            $stmt_update->bindParam(':heure_consultation', $heure_consultation);
            $stmt_update->bindParam(':duree_consultation', $duree_consultation);
            $stmt_update->bindParam(':id_medecin', $id_medecin, PDO::PARAM_INT);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt_update->execute();

            echo "<h3 style='color: green;'>Consultation modifiée avec succès. Vous allez être redirigé vers la page d'affichage des consultations.</h3>";
            header("refresh:3;url=affichage_consultations.php");
        }
    } else {
        // Récupérer l'ID du rendez-vous depuis les paramètres de l'URL
        $id = $_GET["id"];

        // Requête pour récupérer les informations du rendez-vous
        $sql = "SELECT * FROM rendez_vous WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les données du rendez-vous
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Initialiser les variables avec les données du rendez-vous
        $date_consultation = $row["date_consultation"];
        $heure_consultation = $row["heure_consultation"];
        $duree_consultation = $row["duree_consultation"];
        $id_medecin = $row["id_medecin"];
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
    <title>Modifier une Consultation</title>
    <link rel="stylesheet" href="../Css/modifier_consultations.css">
</head>
<body>

    <h2>Modifier une Consultation</h2>

    <?php
    if (!empty($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    ?>

    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="date_consultation">Date de Consultation:</label>
        <input type="date" name="date_consultation" value="<?php echo $date_consultation; ?>" required><br>

        <label for="heure_consultation">Heure de Consultation:</label>
        <input type="time" name="heure_consultation" value="<?php echo $heure_consultation; ?>" required><br>

        <label for="duree_consultation">Durée (en minutes):</label>
        <input type="number" name="duree_consultation" value="<?php echo $duree_consultation; ?>" required><br>

        <label for="id_medecin">Médecin:</label>
        <select name="id_medecin" required>
            <?php
            // Afficher la liste des médecins dans le menu déroulant
            while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($id_medecin == $row_medecin['id']) ? 'selected' : '';
                echo "<option value='{$row_medecin["id"]}' $selected>{$row_medecin["prenom"]} {$row_medecin["nom"]}</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Enregistrer Modification">
    </form>

    <?php
    include('footer.php');
    ?>

</body>
</html>
