<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
    <title>Mes cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        #notesTable {
            display: <?php echo isset($_GET['cours_code']) ? 'table' : 'none'; ?>;
        }
        .message {
            text-align: center;
            color: #888;
            font-style: italic;
        }
        .class-info, .student-list {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .class-info {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php require_once '../../header.php'; ?>
<?php

if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
    $user = $_SESSION['user'];
    $utype = $_SESSION['utype'];

    $mat = $user['Matricule'];

    if ($utype == 'Etudiant') {
        require_once '../../dbconfig.php';

        try {
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * 
                            FROM ETUDIANT, CLASSE, FILIERE 
                            WHERE FILIERE.Code_fil=CLASSE.c_fil
                            AND CLASSE.Code_cl=ETUDIANT.c_cl
                            AND Matricule = :mat
                            ");
            $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
            $stmt->execute();
            $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($etudiant) {
                // Extract the etudiant details
                $matri_etd = htmlspecialchars($etudiant['Matricule']);
                $nom_etd = htmlspecialchars($etudiant['Nom']);
                $pren_etd = htmlspecialchars($etudiant['Prenom']);
                $fil_etd = htmlspecialchars($etudiant['Libelle_fil']);
                $class_etd = htmlspecialchars($etudiant['Libelle']);
        
            } else {
                echo "classe non trouvé.";
                exit();
            }

            //Classe
            $stmt3 = $pdo->prepare("SELECT c_cl
            FROM ETUDIANT
            WHERE Matricule = :mat;
            ");
            $stmt3->bindParam(':mat', $mat, PDO::PARAM_INT);
            $stmt3->execute();
            $classe = $stmt3->fetch(PDO::FETCH_ASSOC);

            if ($classe) {
                $code_classe = $classe['c_cl'];
            }

            // Fetching courses
            $stmt = $pdo->prepare("SELECT *
                                    FROM SUIVRE, ETUDIANT, COURS, ENSEIGNANT 
                                    WHERE ENSEIGNANT.Code_ens=COURS.c_ens
                                    AND COURS.Code_cours=SUIVRE.Code_cours
                                    AND ETUDIANT.Matricule=SUIVRE.matricule
                                    AND SUIVRE.matricule =:mat
            ");
            /*$stmt = $pdo->prepare("
                SELECT c.Intitule, c.Coefficient, e2.Nom AS EnseignantNom, e2.Prenom AS EnseignantPrenom
                FROM COURS c
                INNER JOIN SUIVRE s ON c.Code_cours = s.Code_cours
                INNER JOIN ETUDIANT e ON s.Matricule = e.Matricule
                INNER JOIN ENSEIGNANT e2 ON c.c_ens = e2.Code_ens
                WHERE e.Matricule = (
                    SELECT e.Matricule
                    FROM ETUDIANT e
                    INNER JOIN SUIVRE s ON e.Matricule = s.Matricule
                    WHERE e.c_cl = :code_classe
                    GROUP BY e.Matricule
                    ORDER BY COUNT(s.Code_cours) DESC
                    LIMIT 1
            ");*/
            //$stmt->bindParam(':code_classe', $code_classe, PDO::PARAM_INT);
            $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
            $stmt->execute();
            $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            

        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit();
        }
    } else {
        header('Location: ../error.html');
        exit();
    }
} else {
    header('Location: ../error.html');
    exit();
}
?>


<div class="class-info">
        <h2>Etudiant</h2>
        <p><strong>Matricule :</strong> <?= $matri_etd ?></p>
        <p><strong>Nom :</strong> <?= $nom_etd ?></p>
        <p><strong>Prénoms :</strong> <?= $pren_etd ?></p>
        <p><strong>Filière :</strong> <?= $fil_etd ?></p>
        <p><strong>Classe :</strong> <?= $class_etd ?></p>
</div>

<div style="margin: 30px;">
    <h2>Mes cours</h2>
    <table id="coursTable">
        <thead>
            <tr>
                <th>Cours</th>
                <th>Coefficient</th>
                <th>Enseignant</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cours as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['Intitule']); ?></td>
                <td><?php echo htmlspecialchars($course['Coefficient']); ?></td>
                <td><?php echo htmlspecialchars($course['Nom'])." ".htmlspecialchars($course['Prenom']); ?></td>
                <td><?php echo htmlspecialchars($course['Contact']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>
