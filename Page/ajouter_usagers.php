<?php
include('menu.php');
include('bdd.php');

// Initialiser les variables pour stocker les valeurs du formulaire
$civilite = $nom = $prenom = $adresse = $date_naissance = $lieu_naissance = $num_secu_sociale = $id_medecin_referent = "";
$error_message = "";

try {
    // Requête pour récupérer la liste des médecins (pour la liste déroulante du médecin référent)
    $sql_medecins = "SELECT id, nom, prenom FROM medecins";
    $result_medecins = $conn->query($sql_medecins);

    // Traitement du formulaire d'ajout du patient
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire, y compris l'id du médecin référent
        $civilite = $_POST["civilite"];
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $adresse = $_POST["adresse"];
        $date_naissance = $_POST["date_naissance"];
        $lieu_naissance = $_POST["lieu_naissance"];
        $num_secu_sociale = $_POST["num_secu_sociale"];
        $id_medecin_referent = $_POST["id_medecin_referent"];

        // Vérification de la longueur du numéro de sécurité sociale
        if (strlen($num_secu_sociale) !== 15) {
            $error_message = "Le numéro de sécurité sociale doit avoir exactement 15 caractères.";
        } else {
            // Vérification si l'utilisateur a sélectionné un médecin référent
            if (!empty($id_medecin_referent)) {
                // Vérifier si l'ID du médecin référent existe dans la table medecins
                $stmt_check_medecin = $conn->prepare("SELECT COUNT(*) AS count_medecin FROM medecins WHERE id = :id_medecin_referent");
                $stmt_check_medecin->bindParam(':id_medecin_referent', $id_medecin_referent);
                $stmt_check_medecin->execute();
                $result_check_medecin = $stmt_check_medecin->fetch(PDO::FETCH_ASSOC);

                // Si le médecin référent n'existe pas, afficher un message d'erreur
                if ($result_check_medecin['count_medecin'] === 0) {
                    $error_message = "Le médecin référent spécifié n'existe pas.";
                } else {
                    // Requête pour insérer un nouveau patient avec le médecin référent
                    $sql = "INSERT INTO usagers (civilite, nom, prenom, adresse, date_naissance, lieu_naissance, num_secu_sociale, id_medecin_referent) 
                            VALUES (:civilite, :nom, :prenom, :adresse, :date_naissance, :lieu_naissance, :num_secu_sociale, :id_medecin_referent)";

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':civilite', $civilite);
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenom', $prenom);
                    $stmt->bindParam(':adresse', $adresse);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':lieu_naissance', $lieu_naissance);
                    $stmt->bindParam(':num_secu_sociale', $num_secu_sociale);
                    $stmt->bindParam(':id_medecin_referent', $id_medecin_referent);

                    $stmt->execute();

                    echo "<h3 style='color: green;'>Patient ajouté avec succès. Vous allez être redirigé vers la page d'affichage des patients.</h3>";
                    header("refresh:3;url=affichage_usagers.php");
                }
            } else {
                // Requête pour insérer un nouveau patient sans médecin référent
                $sql = "INSERT INTO usagers (civilite, nom, prenom, adresse, date_naissance, lieu_naissance, num_secu_sociale) 
                        VALUES (:civilite, :nom, :prenom, :adresse, :date_naissance, :lieu_naissance, :num_secu_sociale)";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':civilite', $civilite);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':lieu_naissance', $lieu_naissance);
                $stmt->bindParam(':num_secu_sociale', $num_secu_sociale);

                $stmt->execute();

                echo "<h3 style='color: green;'>Patient ajouté avec succès. Vous allez être redirigé vers la page d'affichage des patients.</h3>";
                header("refresh:3;url=affichage_usagers.php");
            }
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
    <title>Ajouter un patient</title>
    <link rel="stylesheet" href="../Css/ajouter_usagers.css">
</head>
<body>

    <h2>Ajouter un patient</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="civilite">Civilité:</label>
        <select name="civilite" required>
            <option value="M.">M.</option>
            <option value="Mme">Mme</option>
        </select><br>

        <label for="nom">Nom:</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required><br>

        <label for="adresse">Adresse:</label>
        <input type="text" name="adresse" required><br>

        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance" required><br>

        <label for="lieu_naissance">Lieu de Naissance:</label>
        <input type="text" name="lieu_naissance" required><br>

        <label for="num_secu_sociale">Numéro de Sécurité Sociale:</label>
        <input type="text" name="num_secu_sociale" maxlength="15" required><br>

        <!-- Ajout de la liste déroulante pour le médecin référent -->
        <label for="id_medecin_referent">Médecin Référent:</label>
        <select name="id_medecin_referent">
            <option value="">Aucun Médecin Référent</option>
            <?php
            // Afficher la liste des médecins
            while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='".$row_medecin["id"]."'>".$row_medecin["prenom"]." ".$row_medecin["nom"]."</option>";
            }
            ?>
        </select><br>

        <?php if (!empty($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <input type="submit" value="Enregistrer Usager">
    </form>

    <?php
        include('footer.php');
    ?>

</body>
</html>
