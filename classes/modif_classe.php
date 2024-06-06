<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Classe</title>
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
    
    // Vérifier les autorisations de l'utilisateur
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];
    
        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Include your database configuration file
            require_once '../dbconfig.php';
    
            // Récupérer le code de la classe à modifier depuis l'URL
            if(isset($_GET['inputClasse'])) {
                $code_cl = htmlspecialchars($_GET['inputClasse']);
                
                // Récupérer les données de la classe depuis la base de données
                try {
                    // Create a new PDO instance
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Préparer une déclaration SELECT pour récupérer les données de la classe
                    $stmt = $pdo->prepare("SELECT c.Code_cl, c.Libelle, c.c_fil, c.Effectif, f.Libelle_fil 
                                      FROM CLASSE c 
                                      INNER JOIN FILIERE f ON c.c_fil = f.Code_fil
                                      WHERE c.Code_cl = :code_cl");
                    $stmt->bindParam(':code_cl', $code_cl);
    
                    // Exécuter la déclaration
                    $stmt->execute();
    
                    // Fetch the classe
                    $classe = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($classe) {
                        // Extraire les données de la classe
                        $code = $classe['Code_cl'];
                        $lib_classe = $classe['Libelle'];
                        $c_fil = $classe['c_fil'];
                        $filiere = $classe['Libelle_fil'];
                        $effectif = $classe['Effectif'];
                    } else {
                        $message = "Classe non trouvée.";
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
                $message = "Paramètre manquant : Code de la classe";
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
        $lib_classe = htmlspecialchars($_POST['lib_classe']);
        $c_fil = htmlspecialchars($_POST['c_fil']);
    
        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Préparer une déclaration UPDATE pour modifier la classe dans la base de données
            $stmt = $pdo->prepare("UPDATE CLASSE SET Libelle = :lib_classe WHERE Code_cl = :code_cl");
            $stmt->bindParam(':lib_classe', $lib_classe);
            $stmt->bindParam(':code_cl', $code_cl);
    
            // Exécuter la déclaration
            if ($stmt->execute()) {
                $message = "Classe modifiée avec succès.";
                include '../flottant_succes.php';
                exit();
            } else {
                $message = "Erreur lors de la modification de la classe.";
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
            <h2>Modifier une classe</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Code de la classe :</label>
                    <input type="text" id="code" name="code" value="<?= $code ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="effectif">Effectif de la classe :</label>
                    <input type="text" id="effectif" name="effectif" value="<?= $effectif ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="filiere">Filière :</label>
                    <input type="text" id="filiere" name="filiere" value="<?= $filiere ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="lib_classe">Libellé :</label>
                    <input type="text" id="lib_classe" name="lib_classe" value="<?= $lib_classe ?>" required>
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
