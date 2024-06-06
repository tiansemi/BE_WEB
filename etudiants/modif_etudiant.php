<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Enseignant</title>
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
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];
    
        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Include your database configuration file
            require_once '../dbconfig.php';
    
            // Récupérer le code de l'étudiant à modifier depuis l'URL
            if(isset($_GET['inputMat'])) {
                $code_etu = htmlspecialchars($_GET['inputMat']);
                $etudiant=null;
                
                // Récupérer les données de l'étudiant depuis la base de données
                try {
                    // Create a new PDO instance
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Préparer une déclaration SELECT pour récupérer les données de l'étudiant
                    $stmt = $pdo->prepare("SELECT * FROM ETUDIANT WHERE Matricule = :code_etu");
                    $stmt->bindParam(':code_etu', $code_etu);
    
                    // Exécuter la déclaration
                    $stmt->execute();
    
                    // Fetch the étudiant
                    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($etudiant) {
                        // Extraire les données de l'étudiant
                        $code = $etudiant['Matricule'];
                        $nom = $etudiant['Nom'];
                        $prenoms = $etudiant['Prenom'];
                        $date_naiss = $etudiant['Date_naiss'];
                        //$mdp = $etudiant['password'];
                        $lieu_naiss = $etudiant['Lieu_naiss'];
                    } else {
                        $message = "Étudiant non trouvé.";
                        include '../flottant_error.php';
                        exit();
                    }
    
                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;
    
                } catch (PDOException $e) {
                    $message = "Erreur de base de données : " . $e->getMessage();
                }
            } else {
                $message = "Paramètre manquant : Matricule de l'étudiant";
                exit();
            }
        } else {
            header('Location: error.html');
            exit();
        }
    } else {
        header('Location: error.html');
        exit();
    }
    
    // Traitement du formulaire de modification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        //$code_etu = htmlspecialchars($_POST['code']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenoms = htmlspecialchars($_POST['prenoms']);
        $date_naiss = htmlspecialchars($_POST['date_naiss']);
        //$password = hash('sha512', $_POST["password"]); // Hachage du mot de passe
        $lieu_naiss = htmlspecialchars($_POST['lieu_naiss']);
    
        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Préparer une déclaration UPDATE pour modifier l'étudiant dans la base de données
            $stmt = $pdo->prepare("UPDATE ETUDIANT SET Nom = :nom, Prenom = :prenoms, Date_Naiss = :date_naiss, Lieu_naiss = :lieu_naiss WHERE Matricule = :code_etu");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenoms', $prenoms);
            $stmt->bindParam(':date_naiss', $date_naiss);
            $stmt->bindParam(':lieu_naiss', $lieu_naiss);
            //$stmt->bindParam(':password', $password);
            $stmt->bindParam(':code_etu', $code_etu);
    
            // Exécuter la déclaration
            if ($stmt->execute()) {
                $message="Étudiant modifié avec succès";
                include '../flottant_succes.php';
                exit();
                
            } else {
                $message = "Erreur lors de la modification de l'étudiant.";
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
            <h2>Modifier un Étudiant</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="nom">Matricule/Code :</label>
                    <input type="text" id="code" name="code" value="<?= $code ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="<?= $nom ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenoms">Prénoms :</label>
                    <input type="text" id="prenoms" name="prenoms" value="<?= $prenoms ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_naiss">Date de Naissance :</label>
                    <input type="text" id="date_naiss" name="date_naiss" value="<?= $date_naiss ?>" placeholder="YYYY-MM-DD" required >
                </div>
                <div class="form-group">
                    <label for="lieu_naiss">Lieu de Naissance :</label>
                    <input type="text" id="lieu_naiss" name="lieu_naiss" required>
                </div>
                <div class="form-group">
                    <label for="date_naiss">Mot de passe :</label>
                    <input type="text" id="mdp" name="mdp" value="******" disabled>
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
