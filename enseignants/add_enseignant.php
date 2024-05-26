<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Enseignant</title>
    <link rel="stylesheet" href="/be_web/fontawesome.all.min.css">
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
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .form-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Header Logout -->
    <?php if ($_SERVER["REQUEST_METHOD"] != "POST" && !isset($_GET['prenoms'])) {require_once '../header.php';} ?>

<?php
    if (!isset($_SESSION)) {session_start();}
    $message = "";

    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Enseignant' && $user['status'] == 'ad') {
            // Inclure votre fichier de configuration de la base de données
            require_once '../dbconfig.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                $code = htmlspecialchars($_POST['code']);
                $nom = htmlspecialchars($_POST['nom']);
                $prenoms = htmlspecialchars($_POST['prenoms']);
                $contact = htmlspecialchars($_POST['contact']);
                $password = hash('sha512', $_POST["password"]); // Hachage du mot de passe
                $status = htmlspecialchars($_POST['status']);

                try {
                    // Créer une nouvelle instance PDO
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Préparer une déclaration INSERT pour ajouter un nouvel enseignant dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO ENSEIGNANT (Code_ens, Nom, Prenom, Contact, password, status) VALUES (:code, :nom, :prenoms, :contact, :password, :status)");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenoms', $prenoms);
                    $stmt->bindParam(':contact', $contact);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':status', $status);

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        include 'flottant_succes.html';
                        exit();
                    } else {
                        $message = "Erreur lors de l'ajout de l'enseignant.";
                    }

                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;

                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        // Gérer spécifiquement les violations de contrainte d'intégrité
                        echo "<script> alert(\"Erreur : Matricule de l'enseignant déjà utilisé.\"); </script>";
                    } else {
                        // Gérer les autres erreurs de base de données
                        $message = "Erreur de base de données : " . $e->getMessage();
                    }
                }
            }

        } else {
            echo "<script>alert('Vous n'êtes pas un administrateur');</script>";
            header('Location: ../error.html');
            exit();
        }
    } else {
        header('Location: ../error.html');
        exit();
    }
?>

    <div id="body">
        <div class="form-container mt-2 mb-3">
            <h2>Ajouter un Enseignant</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="code">Matricule/Code :</label>
                    <input type="text" id="code" name="code" required onchange="fillPassword()">
                </div>
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenoms">Prénoms :</label>
                    <input type="text" id="prenoms" name="prenoms" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact :</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <!-- Icône d'œil -->
                    <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer; display: initial;" onclick="togglePassword()"></i>
                </div>
                <div class="form-group">
                    <label for="status">Statut :</label>
                    <select id="status" name="status" required>
                        <option value="ad">ad</option>
                        <option value="pp">pp</option>
                        <option value="pp">actif</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit">Ajouter</button>
                </div>
            </form>
            <div class="message"><?php echo $message; ?></div>
        </div>
    </div>
</body>
</html>

<script>
    function fillPassword() {
        // Récupérer la valeur du champ de code/matricule
        var codeValue = document.getElementById('code').value;

        // Générer un mot de passe basé sur la valeur de code/matricule
        var generatedPassword = codeValue;

        // Mettre à jour la valeur du champ de mot de passe
        document.getElementById('password').value = generatedPassword;
    }

    function togglePassword() {
        var passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Basculer l'icône d'œil barré
        document.querySelector('#togglePassword').classList.toggle('fa-eye-slash');
    }
</script>
