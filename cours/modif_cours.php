<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Cours</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>

<?php
    $message = "";
    
    // Vérifier les autorisations de l'utilisateur
    if (!isset($_SESSION)) { session_start(); }
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];
    
        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Inclure le fichier de configuration de la base de données
            require_once '../dbconfig.php';
    
            // Récupérer le code du cours à modifier depuis l'URL
            if (isset($_GET['inputCours'])) {
                $code_cours = htmlspecialchars($_GET['inputCours']);
                
                // Récupérer les données du cours depuis la base de données
                try {
                    // Créer une nouvelle instance PDO
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Préparer une déclaration SELECT pour récupérer les données du cours
                    $stmt = $pdo->prepare("SELECT * FROM COURS WHERE Code_cours = :code_cours");
                    $stmt->bindParam(':code_cours', $code_cours);
    
                    // Exécuter la déclaration
                    $stmt->execute();
    
                    // Récupérer le cours
                    $cours = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($cours) {
                        // Extraire les données du cours
                        $code = $cours['Code_cours'];
                        $intitule = $cours['Intitule'];
                        $c_ens = $cours['c_ens'];
                    } else {
                        $message = "Cours non trouvé.";
                        include "../flottant_error.php";
                        exit();
                    }

                    // Récupérer les enseignants pour le menu déroulant
                    $stmt2 = $pdo->prepare("SELECT Code_ens, Nom, Prenom FROM ENSEIGNANT");
                    $stmt2->execute();
                    $enseignants = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $stmt2 = null;
                    $pdo = null;
    
                } catch (PDOException $e) {
                    $message = "Erreur de base de données : " . $e->getMessage();
                }
            } else {
                $message = "Paramètre manquant : Code du cours";
                exit();
            }
        } else {
            header('Location: ../error.html');
            exit();
        }
    } else {
        header('Location: ../error.html');
        exit();
    }
    
    // Traitement du formulaire de modification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $intitule = htmlspecialchars($_POST['intitule']);
        $c_ens = htmlspecialchars($_POST['c_ens']);
    
        try {
            // Créer une nouvelle instance PDO
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Préparer une déclaration UPDATE pour modifier le cours dans la base de données
            $stmt = $pdo->prepare("UPDATE COURS SET Intitule = :intitule, c_ens = :c_ens WHERE Code_cours = :code_cours");
            $stmt->bindParam(':intitule', $intitule);
            $stmt->bindParam(':c_ens', $c_ens);
            $stmt->bindParam(':code_cours', $code_cours);
    
            // Exécuter la déclaration
            if ($stmt->execute()) {
                $message = "Cours modifié avec succès.";
                include '../flottant_succes.php';
                exit();
            } else {
                $message = "Erreur lors de la modification du cours.";
            }
    
            // Fermer la déclaration et la connexion à la base de données
            $stmt = null;
            $pdo = null;
    
        } catch (PDOException $e) {
            $message = "Erreur de base de données : " . $e->getMessage();
        }
    }
?>


    <div id="body">
        <div class="form-container mt-2 mb-3">
            <h2>Modifier un Cours</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Code :</label>
                    <input type="text" id="code" name="code" value="<?= $code ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="intitule">Intitulé :</label>
                    <input type="text" id="intitule" name="intitule" value="<?= $intitule ?>" required>
                </div>
                <div class="form-group">
                    <label for="c_ens">Enseignant :</label>
                    <select id="c_ens" name="c_ens" required>
                        <option value="">Choisir un enseignant</option>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo htmlspecialchars($enseignant['Code_ens']); ?>" <?php if ($enseignant['Code_ens'] == $c_ens) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($enseignant['Code_ens'] . ' - ' . $enseignant['Nom'] . ' ' . $enseignant['Prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit">Modifier</button>
                </div>
            </form>
            <div class="message"><?= $message ?></div>
        </div>
    </div>
</body>
</html>
