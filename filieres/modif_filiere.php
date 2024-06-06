<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Filière</title>
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
            // Inclure le fichier de configuration de la base de données
            require_once '../dbconfig.php';
    
            // Récupérer le code de la filière à modifier depuis l'URL
            if(isset($_GET['inputFil'])) {
                $code_fil = htmlspecialchars($_GET['inputFil']);
                
                // Récupérer les données de la filière depuis la base de données
                try {
                    // Créer une nouvelle instance PDO
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Préparer une déclaration SELECT pour récupérer les données de la filière
                    $stmt = $pdo->prepare("SELECT * FROM FILIERE WHERE Code_fil = :code_fil");
                    $stmt->bindParam(':code_fil', $code_fil);
    
                    // Exécuter la déclaration
                    $stmt->execute();
    
                    // Récupérer la filière
                    $filiere = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($filiere) {
                        // Extraire les données de la filière
                        $code = $filiere['Code_fil'];
                        $libelle = $filiere['Libelle_fil'];
                    } else {
                        $message = "Filière non trouvée.";
                        include "../flottant_error.php";
                        exit();
                    }
    
                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;
    
                } catch (PDOException $e) {
                    $message = "Erreur de base de données : " . $e->getMessage();
                }
            } else {
                $message = "Paramètre manquant : Code de la filière";
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
        $libelle = htmlspecialchars($_POST['libelle']);
    
        try {
            // Créer une nouvelle instance PDO
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Préparer une déclaration UPDATE pour modifier la filière dans la base de données
            $stmt = $pdo->prepare("UPDATE FILIERE SET Libelle_fil = :libelle WHERE Code_fil = :code_fil");
            $stmt->bindParam(':libelle', $libelle);
            $stmt->bindParam(':code_fil', $code_fil);
    
            // Exécuter la déclaration
            if ($stmt->execute()) {
                $message = "Filière modifiée avec succès.";
                include '../flottant_succes.php';
                exit();
            } else {
                $message = "Erreur lors de la modification de la filière.";
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
            <h2>Modifier une Filière</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Code :</label>
                    <input type="text" id="code" name="code" value="<?= $code ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="libelle">Libellé :</label>
                    <input type="text" id="libelle" name="libelle" value="<?= $libelle ?>" required>
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
