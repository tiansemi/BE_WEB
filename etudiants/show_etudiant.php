<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information de l'Étudiant</title>
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
            max-width: 400px;
            width: 100%;
            text-align: center;
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
                $etudiant_code = $_GET["inputMat"];
                $stmt = $pdo->prepare("SELECT E.*, C.Libelle AS Classe, C.Effectif AS Effectif, F.Libelle_fil AS Filiere
                    FROM ETUDIANT E
                    INNER JOIN CLASSE C ON E.c_cl = C.Code_cl
                    INNER JOIN FILIERE F ON C.c_fil = F.Code_fil WHERE E.Matricule = :inputMat");
                $stmt->bindParam(':inputMat', $etudiant_code, PDO::PARAM_INT);

                // Execute the statement
                $stmt->execute();

                // Fetch the enseignant
                $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($etudiant) {
                    // Extract the enseignant details
                    $code_etd = htmlspecialchars($etudiant['Matricule']);
                    $nom = htmlspecialchars($etudiant['Nom']);
                    $prenoms = htmlspecialchars($etudiant['Prenom']);
                    $date_naiss = htmlspecialchars($etudiant['Date_naiss']);
                    $mot_de_passe = htmlspecialchars($etudiant['password']);
                    $lieu_naiss = htmlspecialchars($etudiant['Lieu_naiss']);
                } else {
                    echo "Étudiant non trouvé.";
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
            <h2>Informations de l'Étudiant</h2>
            <div class="photo-container">
                <img src="profil.png" alt="Photo de l'enseignant" class="photo">
            </div>
            <div class="info">
                <span class="label">Matricule :</span>
                <span class="value" id="matricule"><?= $code_etd ?></span>
            </div>
            <div class="info">
                <span class="label">Nom :</span>
                <span class="value" id="nom"><?= $nom ?></span>
            </div>
            <div class="info">
                <span class="label">Prénoms :</span>
                <span class="value" id="prenoms"><?= $prenoms ?></span>
            </div>
            <div class="info">
                <span class="label">Date de Naissance :</span>
                <span class="value" id="date_naiss"><?= $date_naiss ?></span>
            </div>
            <div class="info">
                <span class="label">Lieu de Naissance :</span>
                <span class="value" id="lieu_naiss"><?= $lieu_naiss ?></span>
            </div>
            <div class="info">
                <span class="label">Mot de passe :</span>
                <span class="value" id="mot-de-passe">**********</span>
            </div>

            <!-- Ajout des boutons de modification et de suppression -->
            <div class="buttons-container">
                <form action="modif_etudiant.php" class="left-button">
                    <input type="hidden" name="inputMat" value="<?= $code_etd ?>">
                    <button type="submit">Modifier</button>
                </form>
                <form id="deleteForm" action="del_etudiant.php" class="right-button" onsubmit="return confirmDelete()">
                    <input type="hidden" name="inputMat" value="<?= $code_etd ?>">
                    <button type="submit">Supprimer</button>
                </form>
                <script>
                    function confirmDelete() {
                        // Demander la confirmation de suppression
                        if (confirm("Êtes-vous sûr de vouloir supprimer cet enseignant?")) {
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
