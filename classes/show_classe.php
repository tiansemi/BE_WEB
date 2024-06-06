<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de la Classe</title>
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

        .teacher-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        table tbody tr td {
            width: 110px;
        }

        .photo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .teacher-card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #888;
        }

        #mot-de-passe {
            font-family: 'Courier New', Courier, monospace;
        }

        .buttons-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        }

        .buttons-container form {
            display: inline-block;
        }

        .left-button button {
            background-color: gray; /* Couleur de fond du bouton Modifier */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .right-button button {
            background-color: #dc3545; /* Couleur de fond du bouton Supprimer */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>


<?php
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Include your database configuration file
            require_once '../dbconfig.php';

            try {
                // Create a new PDO instance
                $pdo = new PDO($dsn, $user, $pass, $opt);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare a SELECT statement to fetch a specific enseignant from the database
                $classe_code = $_GET["inputMat"];
                $stmt = $pdo->prepare("SELECT *
                                        FROM CLASSE, FILIERE
                                        WHERE FILIERE.Code_fil = CLASSE.c_fil
                                        AND CLASSE.Code_cl = :inputMat");
                $stmt->bindParam(':inputMat', $classe_code, PDO::PARAM_INT);

                // Execute the statement
                $stmt->execute();

                // Fetch the classe
                $classe = $stmt->fetch(PDO::FETCH_ASSOC);



                //.........
                $stmt2 = $pdo->prepare(" SELECT *
                                        FROM CLASSE, ETUDIANT
                                        WHERE CLASSE.Code_cl = ETUDIANT.c_cl
                                        AND ETUDIANT.c_cl = :inputMat
                                    ");
                $stmt2->bindParam(':inputMat', $classe_code, PDO::PARAM_INT);

                // Execute the statement
                $stmt2->execute();

                // Fetch all étudiants
                $etudiants = $stmt2->fetchAll();

                
                if ($classe) {
                    // Extract the classe details
                    $code_cls = htmlspecialchars($classe['Code_cl']);
                    $filiere = htmlspecialchars($classe['Libelle_fil']);
                    $Lib_classe = htmlspecialchars($classe['Libelle']);
                    $Effectif = htmlspecialchars($classe['Effectif']);
            
                } else {
                    echo "classe non trouvé.";
                    exit();
                }
                // Close the statement and the database connection
                $stmt = null;
                $pdo = null;

            } catch (PDOException $e) {
                echo "Erreur de base de données : " . $e->getMessage();
                exit();
            }

        } else {
            echo "Accès refusé: type d'utilisateur incorrect ou statut incorrect.";
            header('Location: ../error.html');
            exit();
        }
    } else {
        echo "Accès refusé: session non définie.";
        header('Location: ../error.html');
        exit();
    }
?>


    <div id="body">
        <div class="teacher-card mt-2 mb-3">
            <h2>Informations de la classe</h2>
            <div class="info">
                <span class="label">Code classe:</span>
                <span class="value" id="code_cls"><?= $code_cls ?></span>
            </div>
            <div class="info">
                <span class="label">Filière :</span>
                <span class="value" id="filiere"><?= $filiere ?></span>
            </div>
            <div class="info">
                <span class="label">Libellé :</span>
                <span class="value" id="lib_classe"><?= $Lib_classe ?></span>
            </div>
            <div class="info">
                <span class="label">Effectif :</span>
                <span class="value" id="effectif"><?= $Effectif ?></span>
            </div>

            <div class="student-list">
                <h2>Liste des étudiants de la classe</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Date de naissance</th>
                            <th>Lieu de naissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $etudiant): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($etudiant['Matricule']); ?></td>
                                        <td><?php echo htmlspecialchars($etudiant['Nom']); ?></td>
                                        <td><?php echo htmlspecialchars($etudiant['Prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($etudiant['Date_naiss']); ?></td>
                                        <td><?php echo htmlspecialchars($etudiant['Lieu_naiss']); ?></td>
                                        <!-- Add more columns as needed -->
                                    </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ajout des boutons de modification et de suppression -->
            <div class="buttons-container">
                <form action="modif_classe.php" class="left-button">
                    <input type="hidden" name="inputMat" value="<?= $code_cls ?>">
                    <button type="submit">Modifier</button>
                </form>
                <form id="deleteForm" action="del_classe.php" class="right-button" onsubmit="return confirmDelete()">
                    <input type="hidden" name="inputMat" value="<?= $code_cls ?>">
                    <button type="submit">Supprimer</button>
                </form>
                <script>
                    function confirmDelete() {
                        // Demander la confirmation de suppression
                        if (confirm("Êtes-vous sûr de vouloir supprimer cette classe ?")) {
                            return true; // Confirmer la suppression
                        } else {
                            return false; // Annuler la suppression
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</body>

</html>
