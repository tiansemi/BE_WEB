<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Infos</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
    <style>
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
    <?php require_once '../../header.php'; ?>


<?php
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        $mat = $user['Matricule'];


        if ($utype == 'Etudiant') {
            require_once '../../dbconfig.php';

            try {
                // Create a new PDO instance
                $pdo = new PDO($dsn, $user, $pass, $opt);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare a SELECT statement to fetch a specific etudiant from the database
                $stmt = $pdo->prepare("SELECT * FROM ETUDIANT WHERE Matricule = :mat");
                $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);

                // Execute the statement
                $stmt->execute();

                // Fetch the enseignant
                $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($etudiant) {
                    // Extract the enseignant details
                    $matricule = htmlspecialchars($etudiant['Matricule']);
                    $nom = htmlspecialchars($etudiant['Nom']);
                    $prenoms = htmlspecialchars($etudiant['Prenom']);
                    $date_naiss = htmlspecialchars($etudiant['Date_naiss']);
                    $lieu_naiss = htmlspecialchars($etudiant['Lieu_naiss']);
                    //$statut = htmlspecialchars($etudiant['status']);
                } else {
                    echo "Enseignant non trouvé.";
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
            <h2>Mes infos</h2>
            <div class="photo-container">
                <img src="profil.png" alt="Photo de l'enseignant" class="photo">
            </div>
            <div class="info">
                <span class="label">Matricule :</span>
                <span class="value" id="matricule"><?= $matricule ?></span>
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
                <span class="label">Date de naissance :</span>
                <span class="value" id="date_naiss"><?= $date_naiss ?></span>
            </div>
            <div class="info">
                <span class="label">Lieu de naissance :</span>
                <span class="value" id="lieu_naiss"><?= $lieu_naiss ?></span>
            </div>
            

        </div>
    </div>
</body>

</html>
