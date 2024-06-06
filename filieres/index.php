<style>
    .floating-banner {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 255, 0, 0.5); /* Couleur verte transparente */
        padding: 10px;
        border-radius: 5px;
        z-index: 9999; /* Assurez-vous que le ruban flottant est au-dessus de tous les autres éléments */
    }
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filières</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>

<?php
    // Vérifie si l'utilisateur est connecté et si le type d'utilisateur est défini
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        // Récupère les informations sur l'utilisateur et son type
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        // Vérifie si l'utilisateur est un enseignant administrateur
        if ($utype == 'Enseignant' && $user['status']=='ad') {
            // Inclut le fichier de configuration de la base de données
            require_once '../dbconfig.php';

            // Crée une nouvelle instance PDO
            $pdo = new PDO($dsn, $user, $pass, $opt);

            // Prépare une instruction SELECT pour récupérer toutes les filières de la base de données
            $stmt = $pdo->prepare("
                    SELECT *
                    FROM FILIERE
                    ORDER BY Code_fil ASC
                ");

            // Exécute l'instruction
            $stmt->execute();

            // Récupère toutes les filières
            $filieres = $stmt->fetchAll();

            // Ferme l'instruction et la connexion à la base de données
            $stmt = null;
            $pdo = null;
        } else {
            // Redirige vers une page d'erreur si l'utilisateur n'est pas autorisé
            header('Location: ../error.html');
            exit(); // Arrête l'exécution du script pour éviter toute exécution supplémentaire
        }
    } else {
        // Redirige vers une page d'erreur si l'utilisateur n'est pas connecté ou si le type d'utilisateur n'est pas défini
        header('Location: ../error.html');
        exit(); // Arrête l'exécution du script pour éviter toute exécution supplémentaire
    }
?>

    <div class="container">
        <h1>Liste des filières</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Code de la filière</th>
                    <th>Libellé</th>
                    <!-- Ajouter plus de colonnes si nécessaire -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filieres as $filiere): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($filiere['Code_fil']); ?></td>
                        <td><?php echo htmlspecialchars($filiere['Libelle_fil']); ?></td>
                        <!-- Ajouter plus de colonnes si nécessaire -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="../bootstrap.bundle.min.js"></script>
</body>
</html>
