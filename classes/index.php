
<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Classes</title>
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

            // Prepare a SELECT statement to fetch all enseignants from the database
            $stmt = $pdo->prepare("
                    SELECT *
                    FROM CLASSE, FILIERE
                    WHERE FILIERE.Code_fil = CLASSE.c_fil
                    ORDER BY Code_cl ASC
                ");

            // Execute the statement
            $stmt->execute();

            // Fetch all classes
            $classes = $stmt->fetchAll();

            // Close the statement and the database connection
            $stmt = null;
            $pdo = null;
            
        }else{
            header('Location: ../error.html');
        }
    }
?>

                <div class="container">
                    <h1>Liste des classes</h1>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code classe</th>
                                <th>Filière</th>
                                <th>Libellé</th>
                                <th>Effectif</th>
                                <!-- Add more columns as needed -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $classe): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($classe['Code_cl']); ?></td>
                                    <td><?php echo htmlspecialchars($classe['Libelle_fil']); ?></td>
                                    <td><?php echo htmlspecialchars($classe['Libelle']); ?></td>
                                    <td><?php echo htmlspecialchars($classe['Effectif']); ?></td>
                                    <!-- Add more columns as needed -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <script type="text/javascript" src="../bootstrap.bundle.min.js"></script>
            </body>
            </html>