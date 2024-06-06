<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une nouvelle filière</title>
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

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                $code = htmlspecialchars($_POST['code']);
                $libelle = htmlspecialchars($_POST['libelle']);

                try {
                    // Créer une nouvelle instance PDO
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Préparer une déclaration INSERT pour ajouter une nouvelle filière dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO FILIERE (Code_fil, Libelle_fil) VALUES (:code, :libelle)");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':libelle', $libelle);

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        $message = "Filière ajoutée avec succès";
                        include '../flottant_succes.php';
                        exit();
                    } else {
                        $message = "Erreur lors de l'ajout de la filière.";
                    }

                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;

                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        // Gérer spécifiquement les violations de contrainte d'intégrité
                        echo "<script> alert(\"Erreur : Code de la filière déjà utilisé.\"); </script>";
                    } else {
                        // Gérer les autres erreurs de base de données
                        $message = "Erreur de base de données : " . $e->getMessage();
                    }
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
            <h2>Ajouter une nouvelle filière</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Code :</label>
                    <input type="text" id="code" name="code" required>
                </div>
                <div class="form-group">
                    <label for="libelle">Libellé :</label>
                    <input type="text" id="libelle" name="libelle" required>
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
