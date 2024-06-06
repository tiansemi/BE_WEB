<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations sur la Filière</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>


<?php
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Inclure le fichier de configuration de la base de données
            require_once '../dbconfig.php';

            try {
                // Créer une nouvelle instance PDO
                $pdo = new PDO($dsn, $user, $pass, $opt);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparer une déclaration SELECT pour récupérer les informations sur la filière
                $filiere_code = $_GET["inputFil"];
                $stmt = $pdo->prepare("SELECT * FROM FILIERE WHERE Code_fil = :inputFil");
                $stmt->bindParam(':inputFil', $filiere_code, PDO::PARAM_STR);

                // Exécuter la déclaration
                $stmt->execute();

                // Récupérer les informations sur la filière
                $filiere = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($filiere) {
                    // Extraire les détails de la filière
                    $code_fil = htmlspecialchars($filiere['Code_fil']);
                    $libelle_fil = htmlspecialchars($filiere['Libelle_fil']);
                } else {
                    echo "Filière non trouvée.";
                    exit();
                }

                // Fermer la déclaration et la connexion à la base de données
                $stmt = null;
                $pdo = null;

            } catch (PDOException $e) {
                echo "Erreur de base de données : " . $e->getMessage();
                exit();
            }

        } else {
            echo "Accès refusé : type d'utilisateur incorrect ou statut incorrect.";
            header('Location: ../error.html');
            exit();
        }
    } else {
        echo "Accès refusé : session non définie.";
        header('Location: ../error.html');
        exit();
    }
?>


    <div id="body">
        <div class="filiere-card mt-2 mb-3">
            <h2>Informations sur la Filière</h2>
            <div class="info">
                <span class="label">Code :</span>
                <span class="value" id="code_fil"><?= $code_fil ?></span>
            </div>
            <div class="info">
                <span class="label">Libellé :</span>
                <span class="value" id="libelle_fil"><?= $libelle_fil ?></span>
            </div>

            <!-- Ajout des boutons de modification et de suppression -->
            <div class="buttons-container">
                <form action="modif_filiere.php" class="left-button">
                    <input type="hidden" name="inputFil" value="<?= $code_fil ?>">
                    <button type="submit">Modifier</button>
                </form>
                <form id="deleteForm" action="del_filiere.php" class="right-button" onsubmit="return confirmDelete()">
                    <input type="hidden" name="inputFil" value="<?= $code_fil ?>">
                    <button type="submit">Supprimer</button>
                </form>
                <script>
                    function confirmDelete() {
                        // Demander confirmation avant de supprimer la filière
                        if (confirm("Êtes-vous sûr de vouloir supprimer cette filière ?")) {
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
