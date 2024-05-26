<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Enseignant</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <style>
        #body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .form-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
    </style>
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
    
            // Récupérer le code de l'enseignant à modifier depuis l'URL
            if(isset($_GET['inputMat'])) {
                $code_ens = htmlspecialchars($_GET['inputMat']);
                
                // Récupérer les données de l'enseignant depuis la base de données
                try {
                    // Create a new PDO instance
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Préparer une déclaration SELECT pour récupérer les données de l'enseignant
                    $stmt = $pdo->prepare("SELECT * FROM ENSEIGNANT WHERE Code_ens = :code_ens");
                    $stmt->bindParam(':code_ens', $code_ens);
    
                    // Exécuter la déclaration
                    $stmt->execute();
    
                    // Fetch the enseignant
                    $enseignant = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if ($enseignant) {
                        // Extraire les données de l'enseignant
                        $code = $enseignant['Code_ens'];
                        $nom = $enseignant['Nom'];
                        $prenoms = $enseignant['Prenom'];
                        $contact = $enseignant['Contact'];
                        //$mdp = $enseignant['password'];
                        $status = $enseignant['status'];
                    } else {
                        $message = "Enseignant non trouvé.";
                        exit();
                    }
    
                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;
    
                } catch (PDOException $e) {
                    $message = "Erreur de base de données : " . $e->getMessage();
                }
            } else {
                $message = "Paramètre manquant : Matricule de l'enseignant";
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
        //$code_ens = htmlspecialchars($_POST['code']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenoms = htmlspecialchars($_POST['prenoms']);
        $contact = htmlspecialchars($_POST['contact']);
        //$password = hash('sha512', $_POST["password"]); // Hachage du mot de passe
        $status = htmlspecialchars($_POST['status']);
    
        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Préparer une déclaration UPDATE pour modifier l'enseignant dans la base de données
            $stmt = $pdo->prepare("UPDATE ENSEIGNANT SET Nom = :nom, Prenom = :prenoms, Contact = :contact, status = :status WHERE Code_ens = :code_ens");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenoms', $prenoms);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':status', $status);
            //$stmt->bindParam(':password', $password);
            $stmt->bindParam(':code_ens', $code_ens);
    
            // Exécuter la déclaration
            if ($stmt->execute()) {
                $message = "Enseignant modifié avec succès.";                
                include 'flottant_succes2.html';
                exit();
                
            } else {
                $message = "Erreur lors de la modification de l'enseignant.";
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
            <h2>Modifier un Enseignant</h2>
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
                    <label for="contact">Contact :</label>
                    <input type="text" id="contact" name="contact" value="<?= $contact ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact">Mot de passe :</label>
                    <input type="text" id="mdp" name="mdp" value="******" disabled>
                </div>
                <div class="form-group">
                    <label for="status">Statut :</label>
                    <select id="status" name="status" required>
                        <option value="ad" <?= ($status == 'ad') ? 'selected' : '' ?>>ad</option>
                        <option value="pp" <?= ($status == 'pp') ? 'selected' : '' ?>>pp</option>
                        <option value="actif" <?= ($status == 'actif') ? 'selected' : '' ?>>actif</option>
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
