<?php
include('menu.php');
include('bdd.php');


// Initialiser les variables pour stocker les valeurs du formulaire
$id_usager = $id_medecin = $date_consultation = $heure_consultation = $duree_consultation = "";
$error_message = "";

try {
    // Requête pour récupérer la liste des patients
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

        // Vérifier si un rendez-vous existe déjà pour ce médecin ou ce patient à la même date et heure
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
            $error_message = "Un rendez-vous existe déjà pour ce médecin ou ce patient à la même date et heure.";
        } else {
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
    <link rel="stylesheet" href="../Css/saisir_consultations.css">
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
    <label for="id_usager">Patient:</label>
<select name="id_usager" id="id_usager" required>
    <option value="">Sélectionner un usager</option>
    <?php
    // Afficher la liste des patients
    while ($row_usager = $result_usagers->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='".$row_usager["id"]."' data-medecin='".$row_usager["id_medecin_referent"]."'>".$row_usager["prenom"]." ".$row_usager["nom"]."</option>";
    }
    ?>
</select>

<label for="id_medecin">Médecin:</label>
<select name="id_medecin" required id="id_medecin">
    <option value="">Sélectionner un médecin</option>
    <?php
    // Afficher la liste des médecins
    while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='".$row_medecin["id"]."'>".$row_medecin["prenom"]." ".$row_medecin["nom"]."</option>";
    }
    ?>
</select>

        <label for="date_consultation">Date de Consultation:</label>
        <input type="date" name="date_consultation" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="heure_consultation">Heure de Consultation:</label>
        <input type="time" name="heure_consultation" value="<?php echo date('H:i'); ?>" required>

        <label for="duree_consultation">Durée (en minutes):</label>
        <input type="number" name="duree_consultation" value="30" required>

        <input type="submit" value="Enregistrer Consultation">
    </form>

    <?php
    include('footer.php');
    ?>

<script>
document.getElementById('id_usager').addEventListener('change', function() {
    var usagerSelect = this;
    var medecinSelect = document.getElementById('id_medecin');
    
    // Si le patient a un médecin référent, sélectionnez automatiquement ce médecin
    var selectedMedecinId = usagerSelect.options[usagerSelect.selectedIndex].getAttribute('data-medecin');
    
    if (selectedMedecinId !== null) {
        medecinSelect.value = selectedMedecinId;
    } else {
        medecinSelect.value = ""; // Réinitialiser le champ du médecin si le patient n'a pas de médecin référent
    }
});
</script>



</body>
</html>
