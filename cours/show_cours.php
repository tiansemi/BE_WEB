<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information du Cours</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>


<?php
    // Vérifie si l'utilisateur est connecté et si le type d'utilisateur est défini
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        // Récupère les informations sur l'utilisateur et son type
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        // Vérifie si l'utilisateur est un enseignant administrateur
        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Inclut le fichier de configuration de la base de données
            require_once '../dbconfig.php';

            try {
                // Crée une nouvelle instance PDO
                $pdo = new PDO($dsn, $user, $pass, $opt);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Récupère le code du cours depuis la requête GET
                $cours_code = $_GET["inputMat"];

                // Prépare une instruction SELECT pour récupérer un cours spécifique de la base de données
                $stmt = $pdo->prepare("SELECT * FROM COURS WHERE Code_cours = :inputMat");
                $stmt->bindParam(':inputMat', $cours_code, PDO::PARAM_INT);

                // Exécute l'instruction
                $stmt->execute();

                // Récupère les informations du cours
                $cours = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cours) {
                    // Extrait les détails du cours
                    $code_cours = htmlspecialchars($cours['Code_cours']);
                    $c_ens = htmlspecialchars($cours['c_ens']);
                    $intitule = htmlspecialchars($cours['Intitule']);
                    // Ajoute d'autres détails si nécessaire
                } else {
                    echo "Cours non trouvé.";
                    exit();
                }

                // Ferme l'instruction et la connexion à la base de données
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
        <div class="course-card mt-2 mb-3">
            <h2>Informations du Cours</h2>
            <div class="info">
                <span class="label">Code du Cours :</span>
                <span class="value" id="code_cours"><?= $code_cours ?></span>
            </div>
            <div class="info">
                <span class="label">Code enseignant :</span>
                <span class="value" id="c_ens"><?= $c_ens ?></span>
            </div>
            <div class="info">
                <span class="label">Intitulé :</span>
                <span class="value" id="intitule"><?= $intitule ?></span>
            </div>
            <!-- Boutons de modification et de suppression -->
            <div class="buttons-container">
                <form action="modif_cours.php" class="left-button">
                    <input type="hidden" name="inputMat" value="<?= $code_cours ?>">
                    <button type="submit">Modifier</button>
                </form>
                <form id="deleteForm" action="del_cours.php" class="right-button" onsubmit="return confirmDelete()">
                    <input type="hidden" name="inputMat" value="<?= $code_cours ?>">
                    <button type="submit">Supprimer</button>
                </form>
                <script>
                    function confirmDelete() {
                        // Demander la confirmation de suppression
                        if (confirm("Êtes-vous sûr de vouloir supprimer ce cours?")) {
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
