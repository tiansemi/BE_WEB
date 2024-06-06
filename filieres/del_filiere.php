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
        // Include your database configuration file
        require_once '../dbconfig.php';

        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérer le code de l'enseignant à supprimer depuis le formulaire soumis
            if(isset($_GET['inputFil'])) {
                $code_filiere = htmlspecialchars($_GET['inputFil']);

                // Préparer une déclaration DELETE pour supprimer l'enseignant de la base de données
                $stmt = $pdo->prepare("DELETE FROM FILIERE WHERE Code_fil = :code_filiere");
                $stmt->bindParam(':code_filiere', $code_filiere, PDO::PARAM_INT);

                // Exécuter la déclaration
                if ($stmt->execute()) {
                    $message = "Filière supprimée avec succès.";
                    include '../flottant_succes.php';
                    exit();
                } else {
                    echo "Erreur lors de la suppression de la filière.";
                }

                // Fermer la déclaration et la connexion à la base de données
                $stmt = null;
                $pdo = null;
            } else {
                echo "Paramètre manquant : Code de la filière";
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
