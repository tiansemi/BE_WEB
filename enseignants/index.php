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
			    <title>Enseignants</title>
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
				    FROM ENSEIGNANT
				    ORDER BY Code_ens ASC
				");

			// Execute the statement
			$stmt->execute();

			// Fetch all enseignants
			$enseignants = $stmt->fetchAll();

			// Close the statement and the database connection
			$stmt = null;
			$pdo = null;
            
        }else{
			header('Location: ../error.html');
		}
	}
?>

			    <div class="container">
			        <h1>Liste des enseignants</h1>
			        <table class="table table-striped">
			            <thead>
			                <tr>
			                    <th>Matricule</th>
			                    <th>Nom</th>
			                    <th>Prénom</th>
			                    <th>Contact</th>
			                    <th>Mot de passe</th>
			                    <th>Statut</th>
			                    <!-- Add more columns as needed -->
			                </tr>
			            </thead>
			            <tbody>
			                <?php foreach ($enseignants as $enseignant): ?>
			                    <tr>
			                        <td><?php echo htmlspecialchars($enseignant['Code_ens']); ?></td>
			                        <td><?php echo htmlspecialchars($enseignant['Nom']); ?></td>
			                        <td><?php echo htmlspecialchars($enseignant['Prenom']); ?></td>
			                        <td><?php echo htmlspecialchars($enseignant['Contact']); ?></td>
			                        <td><?php echo (isset($enseignant['password']) ? 'DÉFINI' : 'NON DÉFINI'); ?></td>
			                        <td><?php echo htmlspecialchars($enseignant['status']); ?></td>
			                        <!-- Add more columns as needed -->
			                    </tr>
			                <?php endforeach; ?>
			            </tbody>
			        </table>
			    </div>
			    <script type="text/javascript" src="../bootstrap.bundle.min.js"></script>
			</body>
			</html>
