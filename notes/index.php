<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Note</title>
    <link rel="stylesheet" href="/be_web/fontawesome.all.min.css">
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>

<?php
    $message = "";

    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Inclure votre fichier de configuration de la base de données
            require_once '../dbconfig.php';

            try {
                // Créer une nouvelle instance PDO
                $pdo = new PDO($dsn, $user, $pass, $opt);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparer une déclaration SELECT pour récupérer tous les étudiants de la base de données
                $stmt1 = $pdo->prepare("SELECT Matricule, Nom, Prenom FROM ETUDIANT");
                $stmt1->execute();
                $etudiants = $stmt1->fetchAll();

                // Préparer une déclaration SELECT pour récupérer tous les cours de la base de données
                $stmt2 = $pdo->prepare("SELECT Code_cours, Intitule FROM COURS");
                $stmt2->execute();
                $cours = $stmt2->fetchAll();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Récupérer les données du formulaire
                    $matricule = htmlspecialchars($_POST['matricule']);
                    $code_cours = htmlspecialchars($_POST['code_cours']);
                    $notes = htmlspecialchars($_POST['notes']);
                    $date_ob = date('Y-m-d H:i:s'); // Date actuelle

                    // Préparer une déclaration INSERT pour ajouter une nouvelle note dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO SUIVRE (Matricule, Code_cours, Notes, Date_ob) VALUES (:matricule, :code_cours, :notes, :date_ob)");
                    $stmt->bindParam(':matricule', $matricule);
                    $stmt->bindParam(':code_cours', $code_cours);
                    $stmt->bindParam(':notes', $notes);
                    $stmt->bindParam(':date_ob', $date_ob);

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        $message = "Note ajoutée avec succès.";
                        include '../flottant_succes.php';
                        exit();
                    } else {
                        $message = "Erreur lors de l'ajout de la note.";
                    }

                    // Fermer la déclaration
                    $stmt = null;
                }

                // Fermer les déclarations SELECT
                $stmt1 = null;
                $stmt2 = null;

                // Fermer la connexion à la base de données
                $pdo = null;

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    // Gérer spécifiquement les violations de contrainte d'intégrité
                    echo "<script> alert(\"Erreur : Enregistrement déjà existant.\"); </script>";
                } else {
                    // Gérer les autres erreurs de base de données
                    $message = "Erreur de base de données : " . $e->getMessage();
                }
            }

        } else {
            echo "<script>alert('Vous n'êtes pas un administrateur');</script>";
            header('Location: ../error.html');
            exit();
        }
    } else {
        header('Location: ../error.html');
        exit();
    }
?>

    <div id="body">
        <div class="form-container mt-2 mb-3">
            <h2>Ajouter une Note</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="matricule">Matricule :</label>
                    <select id="matricule" name="matricule" required>
                        <option value="">Choisir un étudiant</option>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?php echo htmlspecialchars($etudiant['Matricule']); ?>">
                                <?php echo htmlspecialchars($etudiant['Matricule'] . ' - ' . $etudiant['Nom'] . ' ' . $etudiant['Prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label for="code_cours">Code Cours :</label>
                    <select id="code_cours" name="code_cours" required>
                        <option value="">Choisir un cours</option>
                        <?php foreach ($cours as $cour): ?>
                            <option value="<?php echo htmlspecialchars($cour['Code_cours']); ?>">
                                <?php echo htmlspecialchars($cour['Code_cours'] . ' - ' . $cour['Intitule']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes">Note :</label>
                    <input type="number" step="0.01" id="notes" name="notes" required>
                </div>
                <div class="form-group">
                    <button type="submit">Ajouter</button>
                </div>
            </form>
            <div class="message"><?php echo $message; ?></div>
        </div>
    </div>
</body>
</html>
