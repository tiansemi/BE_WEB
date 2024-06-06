<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Redirection</title>
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
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

            // Récupérer le code du cours à supprimer depuis le formulaire soumis
            if (isset($_GET['inputCours'])) {
                $cours_code = htmlspecialchars($_GET['inputCours']);

                // Préparer une déclaration DELETE pour supprimer le cours de la base de données
                $stmt = $pdo->prepare("DELETE FROM COURS WHERE Code_cours = :inputCours");
                $stmt->bindParam(':inputCours', $cours_code, PDO::PARAM_STR);

                // Exécuter la déclaration
                $stmt->execute();

                // Vérifier si une ligne a été affectée
                if ($stmt->rowCount() > 0) {
                    $message= "Cours supprimé avec succès.";
                    include "../flottant_succes.php";
                    exit();
                } else {
                    $message= "Le cours avec le code spécifié n'existe pas.";
                    include "../flottant_error.php"; 
                    exit();
                }

                // Fermer la déclaration et la connexion à la base de données
                $stmt = null;
                $pdo = null;
            } else {
                $message= "Paramètre manquant : Code du cours";
                include "../flottant_error.php";
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
