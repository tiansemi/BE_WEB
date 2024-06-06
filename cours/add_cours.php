<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
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

                // Préparer une déclaration SELECT pour récupérer tous les enseignants de la base de données
                $stmt2 = $pdo->prepare("SELECT Code_ens, Nom, Prenom FROM ENSEIGNANT");
                $stmt2->execute();
                $enseignants = $stmt2->fetchAll();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Récupérer les données du formulaire
                    $code_cours = htmlspecialchars($_POST['code_cours']);
                    $c_ens = htmlspecialchars($_POST['c_ens']);
                    $intitule = htmlspecialchars($_POST['intitule']);
                    // Ajouter d'autres champs si nécessaire

                    // Préparer une déclaration INSERT pour ajouter un nouveau cours dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO COURS (Code_cours, c_ens, Intitule) VALUES (:code_cours, :c_ens, :intitule)");
                    $stmt->bindParam(':code_cours', $code_cours);
                    $stmt->bindParam(':c_ens', $c_ens);
                    $stmt->bindParam(':intitule', $intitule);
                    // Binder d'autres valeurs si nécessaire

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        $message="Cours ajouté avec succès.";
                        include '../flottant_succes.php';
                        exit();
                    } else {
                        $message = "Erreur lors de l'ajout du cours.";
                    }

                    // Fermer la déclaration
                    $stmt = null;
                }

                // Fermer la déclaration SELECT
                $stmt2 = null;

                // Fermer la connexion à la base de données
                $pdo = null;

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    // Gérer spécifiquement les violations de contrainte d'intégrité
                    echo "<script> alert(\"Erreur : Code du cours déjà utilisé.\"); </script>";
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
            <h2>Ajouter un Cours</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code_cours">Code du Cours :</label>
                    <input type="text" id="code_cours" name="code_cours" required>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label for="c_ens">Code Enseignant :</label>
                    <select id="c_ens" name="c_ens" required>
                        <option value="">Choisir un enseignant</option>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo htmlspecialchars($enseignant['Code_ens']); ?>">
                                <?php echo htmlspecialchars($enseignant['Code_ens'] . ' - ' . $enseignant['Nom'] . ' ' . $enseignant['Prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <a href="/be_web/enseignants/add_enseignant.php" style="text-decoration: none;">Ajouter un nouvel enseignant </a>

                <div class="form-group">
                    <label for="intitule">Intitulé :</label>
                    <input type="text" id="intitule" name="intitule" required>
                </div>
                <!-- Ajouter d'autres champs si nécessaire -->
                <div class="form-group">
                    <button type="submit">Ajouter</button>
                </div>
            </form>
            <div class="message"><?php echo $message; ?></div>
        </div>
    </div>
</body>
</html>
