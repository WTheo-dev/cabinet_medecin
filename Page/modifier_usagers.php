<?php
include('menu.php');
include('bdd.php');


// Initialiser les variables pour stocker les valeurs du formulaire
$id_usager = $civilite = $nom = $prenom = $adresse = $date_naissance = $lieu_naissance = $num_secu_sociale = $id_medecin_referent = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Récupérer l'identifiant du patient à modifier
    $id_usager = $_GET["id"];

    try {
        // Requête pour récupérer les informations du patient à modifier
        $sql = "SELECT * FROM usagers WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_usager);
        $stmt->execute();

        // Récupérer les données du patient
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $civilite = $row["civilite"];
        $nom = $row["nom"];
        $prenom = $row["prenom"];
        $adresse = $row["adresse"];
        $date_naissance = $row["date_naissance"];
        $lieu_naissance = $row["lieu_naissance"];
        $num_secu_sociale = $row["num_secu_sociale"];
        $id_medecin_referent = $row["id_medecin_referent"];

    } catch (PDOException $e) {
        echo "Erreur de récupération des informations du patient : " . $e->getMessage();
    }

    // Requête pour récupérer la liste des médecins (pour la liste déroulante du médecin référent)
    $sql_medecins = "SELECT id, nom, prenom FROM medecins";
    $result_medecins = $conn->query($sql_medecins);

   
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_usager = $_POST["id_usager"];
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
        try {
            // Vérification si le numéro de sécurité sociale existe déjà pour un autre patient
            $sql_check_duplicate = "SELECT COUNT(*) AS count_duplicates FROM usagers WHERE num_secu_sociale = :num_secu_sociale AND id <> :id_usager";
            $stmt_check_duplicate = $conn->prepare($sql_check_duplicate);
            $stmt_check_duplicate->bindParam(':num_secu_sociale', $num_secu_sociale);
            $stmt_check_duplicate->bindParam(':id_usager', $id_usager);
            $stmt_check_duplicate->execute();
            $result_check_duplicate = $stmt_check_duplicate->fetch(PDO::FETCH_ASSOC);

            // Si le numéro de sécurité sociale existe déjà, afficher un message d'erreur
            if ($result_check_duplicate['count_duplicates'] > 0) {
                $error_message = "Un usager avec ce numéro de sécurité sociale existe déjà.";
            } else {
                // Si "Aucun Médecin Référent" est sélectionné, mettre la valeur NULL
                $id_medecin_referent = ($id_medecin_referent === "0") ? null : $id_medecin_referent;

                // Requête pour mettre à jour les informations du patient
                $sql = "UPDATE usagers SET
                        civilite = :civilite,
                        nom = :nom,
                        prenom = :prenom,
                        adresse = :adresse,
                        date_naissance = :date_naissance,
                        lieu_naissance = :lieu_naissance,
                        num_secu_sociale = :num_secu_sociale,
                        id_medecin_referent = :id_medecin_referent
                        WHERE id = :id_usager";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':civilite', $civilite);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':lieu_naissance', $lieu_naissance);
                $stmt->bindParam(':num_secu_sociale', $num_secu_sociale);
                $stmt->bindParam(':id_medecin_referent', $id_medecin_referent);
                $stmt->bindParam(':id_usager', $id_usager);

                $stmt->execute();

                echo "<h3 style='color: green;'>Patient modifié avec succès. Vous allez être redirigé vers la page d'affichage des patients.</h3>";
                header("refresh:3;url=affichage_usagers.php");
            }

        } catch (PDOException $e) {
            echo "Erreur de modification d'usager : " . $e->getMessage();
        }

        
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un patient</title>
    <link rel="stylesheet" href="../Css/modifier_usagers.css">
</head>
<body>

    <h2>Modifier un patient</h2>

    <?php if ($id_usager != "") { ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="id_usager" value="<?php echo $id_usager; ?>">

            <label for="civilite">Civilité:</label>
            <select name="civilite" required>
                <option value="M." <?php if ($civilite == "M.") echo "selected"; ?>>M.</option>
                <option value="Mme" <?php if ($civilite == "Mme") echo "selected"; ?>>Mme</option>
            </select><br>

            <label for="nom">Nom:</label>
            <input type="text" name="nom" value="<?php echo $nom; ?>" required><br>

            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" value="<?php echo $prenom; ?>" required><br>

            <label for="adresse">Adresse:</label>
            <input type="text" name="adresse" value="<?php echo $adresse; ?>" required><br>

            <label for="date_naissance">Date de Naissance:</label>
            <input type="date" name="date_naissance" value="<?php echo $date_naissance; ?>" required><br>

            <label for="lieu_naissance">Lieu de Naissance:</label>
            <input type="text" name="lieu_naissance" value="<?php echo $lieu_naissance; ?>" required><br>

            <label for="num_secu_sociale">Numéro de Sécurité Sociale:</label>
            <input type="text" name="num_secu_sociale" value="<?php echo $num_secu_sociale; ?>" maxlength="15" required><br>

            
            <label for="id_medecin_referent">Médecin Référent:</label>
            <select name="id_medecin_referent">
                <option value="0" <?php if ($id_medecin_referent === null) echo "selected"; ?>>Aucun Médecin Référent</option>
                <?php
                // Afficher la liste des médecins
                while ($row_medecin = $result_medecins->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='".$row_medecin["id"]."'";
                    if ($id_medecin_referent == $row_medecin["id"]) {
                        echo " selected";
                    }
                    echo ">".$row_medecin["prenom"]." ".$row_medecin["nom"]."</option>";
                }
                ?>
            </select><br>

            <input type="submit" value="Modifier Usager">
        </form>
        <div class="error"><?php echo $error_message; ?></div>
    <?php } else {
        echo "Aucun patient sélectionné.";
    } ?>

<?php
    include('footer.php');
    ?>

</body>
</html>
