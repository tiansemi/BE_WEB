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
    <title>Cours</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>

<?php
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status']=='ad') {
            // Include your database configuration file
            require_once '../dbconfig.php';

            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);

            // Prepare a SELECT statement to fetch all cours and their associated enseignant from the database
            $stmt = $pdo->prepare("
                SELECT COURS.Code_cours, COURS.Intitule, ENSEIGNANT.Code_ens, CONCAT(ENSEIGNANT.Nom, ' ', ENSEIGNANT.Prenom) AS Enseignant
                FROM COURS
                INNER JOIN ENSEIGNANT ON COURS.c_ens = ENSEIGNANT.Code_ens
                ORDER BY COURS.Code_cours ASC
            ");

            // Execute the statement
            $stmt->execute();

            // Fetch all cours
            $cours = $stmt->fetchAll();

            // Close the statement and the database connection
            $stmt = null;
            $pdo = null;
        } else {
            header('Location: ../error.html');
            exit(); // Ajout pour éviter toute exécution supplémentaire du code
        }
    } else {
        header('Location: ../error.html');
        exit(); // Ajout pour éviter toute exécution supplémentaire du code
    }
?>

    <div class="container">
        <h1>Liste des cours</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Code du cours</th>
                    <th>Intitulé</th>
                    <th>Matricule de l'enseignant</th>
                    <th>Nom de l'enseignant</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cours as $cours): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cours['Code_cours']); ?></td>
                        <td><?php echo htmlspecialchars($cours['Intitule']); ?></td>
                        <td><?php echo htmlspecialchars($cours['Code_ens']); ?></td>
                        <td><?php echo htmlspecialchars($cours['Enseignant']); ?></td>
                        <!-- Add more columns as needed -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="../bootstrap.bundle.min.js"></script>
</body>
</html>
