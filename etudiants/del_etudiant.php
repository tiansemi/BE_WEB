<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Redirection</title>
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
            margin-top: 150px !important;
        }

        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 300px;
            text-align: center;
            position: relative; /* Add this line */
        }

        .checked-icon {
            width: 50px;
            height: 50px;
            position: absolute;
            top: -25px;
            left: calc(50% - 25px);
        }

        .card-body {
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
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

            // Récupérer le code de l'étudiant à supprimer depuis le formulaire soumis
            if(isset($_GET['inputMat'])) {
                $etudiant_code = $_GET['inputMat'];

                // Préparer une déclaration DELETE pour supprimer l'étudiant de la base de données
                $stmt = $pdo->prepare("DELETE FROM ETUDIANT WHERE Matricule = :inputMat");
                $stmt->bindParam(':inputMat', $etudiant_code, PDO::PARAM_INT);

                // Exécuter la déclaration
                if ($stmt->execute()) {
                ?>
                    <body>
                        <div id="body">
                            <div class="card mt-5">
                                <img src="checked.png" alt="Checked" class="checked-icon">
                                <div class="card-body">
                                    Étudiant modifié avec succès
                                </div>
                            </div>
                        </div>

                        <script>
                            // Attendre 2 secondes avant de rediriger vers le fichier HTML
                            setTimeout(function() {
                                window.location.href = "index.php";
                            }, 2000);
                        </script>
                    </body>
                    </html>
                <?php 
                } else {
                    echo "Erreur lors de la suppression de l'étudiant.";
                }

                // Fermer la déclaration et la connexion à la base de données
                $stmt = null;
                $pdo = null;
            } else {
                echo "Paramètre manquant : Code de l'étudiant";
                exit();
            }
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
