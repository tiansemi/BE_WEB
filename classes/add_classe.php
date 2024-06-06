<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Classe</title>
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
            // Inclure le fichier de configuration de la base de données
            require_once '../dbconfig.php';

            // Créer une nouvelle instance PDO
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérer les filières depuis la base de données
            $stmt = $pdo->prepare("SELECT * FROM FILIERE");
            $stmt->execute(); 
            $filieres = $stmt->fetchAll();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                $code = htmlspecialchars($_POST['code']);
                $filiere = htmlspecialchars($_POST['filiere']);
                $lib_classe = htmlspecialchars($_POST['lib_classe']);
                // Effectif par défaut à 0
                $effectif = 0;

                try {
                    // Préparer une déclaration INSERT pour ajouter une nouvelle classe dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO CLASSE (Code_cl, c_fil, Libelle, Effectif)
                                        VALUES (:code, :filiere, :lib_classe, :effectif)");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':filiere', $filiere);
                    $stmt->bindParam(':lib_classe', $lib_classe);
                    $stmt->bindParam(':effectif', $effectif);

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        $message='Classe ajoutée avec succès';
                        include '../flottant_succes.php';
                        exit();
                    } else {
                        $message = "Erreur lors de l'ajout de la classe.";
                    }

                    // Fermer la connexion à la base de données
                    $stmt = null;
                    $pdo = null;

                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        // Gérer spécifiquement les violations de contrainte d'intégrité
                        echo "<script> alert(\"Erreur : Code de la classe déjà utilisé.\"); </script>";
                    } else {
                        // Gérer les autres erreurs de base de données
                        $message = "Erreur de base de données : " . $e->getMessage();
                    }
                }
            }

        } else {
            // Rediriger vers une page d'erreur si l'utilisateur n'est pas autorisé
            echo "<script>alert('Vous n'êtes pas un administrateur');</script>";
            header('Location: ../error.html');
            exit();
        }
    } else {
        // Rediriger vers une page d'erreur si la session n'est pas définie
        header('Location: ../error.html');
        exit();
    }
    ?>

    <!-- Formulaire d'ajout de classe -->
    <div id="body">
        <div class="form-container mt-2 mb-3">
            <h2>Ajouter une nouvelle classe</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Code de la classe :</label>
                    <input type="text" id="code" name="code">
                </div>
                <div class="form-group">
                    <label for="status">Filière :</label>
                    <select id="filiere" name="filiere" required>
                        <?php foreach ($filieres as $filiere): ?>
                            <option value="<?php echo htmlspecialchars($filiere['Code_fil']);?>"><?= $filiere['Libelle_fil']; ?></option>
                            <!-- Ajouter d'autres colonnes si nécessaire -->
                        <?php endforeach; ?>
                    </select>
                </div>
                <a href="/be_web/filieres/add_filiere.php" style="text-decoration: none;">Ajouter une nouvelle filière </a>
                <div class="form-group">
                    <label for="lib_classe">Libellé :</label>
                    <input type="text" id="lib_classe" name="lib_classe" required>
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
