
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
    <title>Mes cours et mes notes</title>
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
        .message {
            text-align: center;
            color: #888;
            font-style: italic;
        }
        .btn-print {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .cotee {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-bottom: 1%;
            margin-bottom: 1%;
        }
        .element {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            margin-bottom: 5px;
        }
        .element:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            margin-right: 10px;
        }
        .value {
            text-align: right;
        }
        .cotee p {
            margin: 0;
            line-height: 1.5
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
        $note_fin = 0;
        $som_coef = 0;
        $som_notes = 0;
        $moyen_gen = 0.0;
        $total_coef = 0;

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
                    $class_eff = htmlspecialchars($etudiant['Effectif']);
            
                } else {
                    echo "étudiant non trouvé.";
                    exit();
                }
                // Fetching courses
                $stmt = $pdo->prepare("SELECT *
                                        FROM SUIVRE, ETUDIANT, COURS 
                                        WHERE COURS.Code_cours=SUIVRE.Code_cours
                                        AND ETUDIANT.Matricule=SUIVRE.matricule
                                        AND SUIVRE.matricule =:mat
                ");
                $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
                $stmt->execute();
                $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

                // Fetching coef de la classe
                /*$stmt = $pdo->prepare(" SELECT SUM(Coefficient) AS coef_tot
                                        FROM cours, etudiant, suivre, classe
                                        WHERE cours.Code_cours=suivre.Code_cours
                                        AND etudiant.Matricule=suivre.Matricule
                                        AND classe.Code_cl=etudiant.c_cl
                                        AND classe.Code_cl=:code_classe
                                        
                ");*/
                $query = "
                SELECT SUM(c.Coefficient) AS coef_tot
                FROM COURS c
                INNER JOIN SUIVRE s ON c.Code_cours = s.Code_cours
                INNER JOIN ETUDIANT e ON s.Matricule = e.Matricule
                INNER JOIN CLASSE cl ON e.c_cl = cl.Code_cl
                WHERE cl.Code_cl = :code_classe
                AND e.Matricule = (
                    SELECT e2.Matricule
                    FROM ETUDIANT e2
                    INNER JOIN SUIVRE s2 ON e2.Matricule = s2.Matricule
                    WHERE e2.c_cl = :code_classe2
                    GROUP BY e2.Matricule
                    ORDER BY SUM(s2.Notes) DESC
                    LIMIT 1
                )
                ";

                $stmt0 = $pdo->prepare($query);
                //$stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
                $stmt0->bindParam(':code_classe', $code_classe, PDO::PARAM_STR);
                $stmt0->bindParam(':code_classe2', $code_classe, PDO::PARAM_STR);
                $stmt0->execute();
                $coef_tot = $stmt0->fetch(PDO::FETCH_ASSOC);
                $total_coef = $coef_tot['coef_tot'];

                // Requête pour sélectionner et classer les étudiants dans la classe
                $query = "
                    SELECT e.Matricule, e.Nom, e.Prenom, 
                    AVG(s.Notes * c.Coefficient) AS Moyenne
                    FROM ETUDIANT e
                    INNER JOIN SUIVRE s ON e.Matricule = s.Matricule
                    INNER JOIN COURS c ON s.Code_cours = c.Code_cours
                    INNER JOIN CLASSE cl ON e.c_cl = cl.Code_cl
                    WHERE cl.Code_cl = :code_classe
                    GROUP BY e.Matricule, e.Nom, e.Prenom
                    ORDER BY Moyenne DESC;
                    ";

                $stmt_classement = $pdo->prepare($query);
                $stmt_classement->bindParam(':code_classe', $code_classe);
                //$stmt_classement->bindParam(':mat', $mat);
                $stmt_classement->execute();
                $classement = $stmt_classement->fetchAll(PDO::FETCH_ASSOC);

                if (!$classement) {
                    echo "Erreur classement";
                } else {
                    $etd = array(); // Initialisation du tableau etd
                    $rang = 1; // Initialisation du rang

                    foreach ($classement as $row) {
                        // Remplissage du tableau etd avec le matricule et le rang
                        $etd[] = array(
                            'Matricule' => $row['Matricule'],
                            'Rang' => $rang,
                        );
                        // Incrémentation du rang pour la prochaine itération
                        $rang++;
                    }
                }
            } else {
                // Traitement si la classe n'est pas trouvée
                $code_classe = "000";
            }



            } catch (PDOException $e) {
                echo "Erreur de base de données : " . $e->getMessage();
                exit();
            }
        } else {
            header('Location: ../../error.html');
            exit();
        }
    } else {
        header('Location: ../../error.html');
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
    <h2>Mes notes</h2>
    <table id="coursTable">
        <thead>
            <tr>
                <th>Cours</th>
                <th>Note</th>
                <th>Coefficient</th>
                <th>Note final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cours as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['Intitule']); ?></td>
                <td><?php echo htmlspecialchars($course['Notes']); ?></td>
                <td><?php echo htmlspecialchars($course['Coefficient']); ?></td>
                <td><?php $note_fin = $course['Notes']*$course['Coefficient'];
                echo $note_fin; ?>
                </td>
            </tr>
            <?php $som_coef = $som_coef+$course['Coefficient'];
                  $som_notes = $som_notes+$note_fin; 
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php 
        //$moyen_gen =  $som_notes/$som_coef;
        $moyen_gen =  $som_notes/$total_coef;
        //$moyen_gen =  $som_notes/$total_coef;
        $rangg;
        foreach ($etd as $etudiant) {
            if ($etudiant['Matricule'] === $matri_etd) {
                $rangg = $etudiant['Rang'];
                break; // Arrête la boucle une fois que l'étudiant est trouvé
            }
        }
    ?>
    <div class="cotee">
        <div class="element">
            <p class="label">Moyenne générale :</p>
            <p class="value"><?php echo $moyen_gen;?></p>
        </div>
        <div class="element">
            <p class="label">Effectif :</p>
            <p class="value"><?= $class_eff ?></p>
        </div>
        <div class="element">
            <p class="label">Rang :</p>
            <p class="value"><?php echo $rangg;?></p>
        </div>
    </div>

    <a href="generate_pdf.php?moy=<?php echo $moyen_gen;?>&eff=<?php echo $class_eff;?>&rang=<?php echo $rangg;?>" class="btn-print" target="_blank">
    Imprimer le bulletin
    </a>
</div>

</body>
</html>
