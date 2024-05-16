<?php
	session_start();
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status']=='ad') {
			// Include your database configuration file
			require_once '../dbconfig.php';

			// Create a new PDO instance
			$pdo = new PDO($dsn, $user, $pass, $opt);

			// Prepare a SELECT statement to fetch all students from the database
			$stmt = $pdo->prepare("
					SELECT E.*, C.Libelle AS Classe, C.Effectif AS Effectif, F.Libelle_fil AS Filiere
				    FROM ETUDIANT E
				    INNER JOIN CLASSE C ON E.c_cl = C.Code_cl
				    INNER JOIN FILIERE F ON C.c_fil = F.Code_fil
				");

			// Execute the statement
			$stmt->execute();

			// Fetch all students
			$students = $stmt->fetchAll();

			// Close the statement and the database connection
			$stmt = null;
			$pdo = null;
?>
			<!DOCTYPE html>
			<html>
			<head>
			    <meta charset="utf-8">
			    <meta name="viewport" content="width=device-width, initial-scale=1">
			    <title>Etudiants</title>
			    <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
			</head>
			<body>
			    <div class="container">
			        <h1>Liste des étudiants</h1>
			        <table class="table table-striped">
			            <thead>
			                <tr>
			                    <th>Matricule</th>
			                    <th>Nom</th>
			                    <th>Prénom</th>
			                    <th>Date_naiss</th>
			                    <th>Lieu_naiss</th>
			                    <th>password</th>
			                    <th>Classe</th>
			                    <th>Effectif de la Classe</th>
			                    <th>Filière</th>
			                    <!-- Add more columns as needed -->
			                </tr>
			            </thead>
			            <tbody>
			                <?php foreach ($students as $student): ?>
			                    <tr>
			                        <td><?php echo htmlspecialchars($student['Matricule']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Nom']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Prenom']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Date_naiss']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Lieu_naiss']); ?></td>
			                        <td><?php echo (isset($student['password']) ? 'DÉFINI' : 'NON DÉFINI'); ?></td>
			                        <td><?php echo htmlspecialchars($student['Classe']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Effectif']); ?></td>
			                        <td><?php echo htmlspecialchars($student['Filiere']); ?></td>
			                        <!-- Add more columns as needed -->
			                    </tr>
			                <?php endforeach; ?>
			            </tbody>
			        </table>
			    </div>
			    <script type="text/javascript" src="../bootstrap.bundle.min.js"></script>
			</body>
			</html>
	<?php 
		}else{
			header('Location: ../error.html');
		}
	}
?>