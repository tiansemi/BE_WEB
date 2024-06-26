<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouvel étudiant</title>
    <link rel="stylesheet" href="/be_web/fontawesome.all.min.css">
    <link rel="stylesheet" type="text/css" href="/be_web/bootstrap.min.css">
    <script type="text/javascript" src="/be_web/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/be_web/index.css">
</head>
<body>
    <!-- Header Logout -->
    <?php require_once '../header.php'; ?>

<?php
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
                $c_cl = htmlspecialchars($_POST['c_cl']);
                $nom = htmlspecialchars($_POST['nom']);
                $prenoms = htmlspecialchars($_POST['prenoms']);
                $date_naiss = htmlspecialchars($_POST['date_naiss']);
                $password = hash('sha512', $_POST["password"]); // Hachage du mot de passe
                $lieu_naiss = htmlspecialchars($_POST['lieu_naiss']);

                try {
                    // Créer une nouvelle instance PDO
                    $pdo = new PDO($dsn, $user, $pass, $opt);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Début de la transaction
                    $pdo->beginTransaction();

                    // Préparer une déclaration INSERT pour ajouter un nouvel étudiant dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO ETUDIANT (Matricule, c_cl, Nom, Prenom, Date_naiss, Lieu_naiss, password) VALUES (:code, :c_cl, :nom, :prenoms, :date_naiss, :lieu_naiss, :password)");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':c_cl', $c_cl);
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenoms', $prenoms);
                    $stmt->bindParam(':date_naiss', $date_naiss);
                    $stmt->bindParam(':lieu_naiss', $lieu_naiss);
                    $stmt->bindParam(':password', $password);

                    // Exécuter la déclaration
                    if ($stmt->execute()) {
                        // Incrémenter l'effectif de la classe
                        $updateStmt = $pdo->prepare("UPDATE CLASSE SET Effectif = Effectif + 1 WHERE Code_cl = :c_cl");
                        $updateStmt->bindParam(':c_cl', $c_cl);
                        $updateStmt->execute();

                        // Valider la transaction
                        $pdo->commit();

                        include 'flottant_succes.html';
                        exit();
                    } else {
                        // Annuler la transaction en cas d'erreur
                        $pdo->rollBack();
                        $message = "Erreur lors de l'ajout de l'étudiant.";
                    }

                    // Fermer la déclaration et la connexion à la base de données
                    $stmt = null;
                    $pdo = null;

                } catch (PDOException $e) {
                    // Annuler la transaction en cas d'exception
                    $pdo->rollBack();

                    if ($e->getCode() == 23000) {
                        // Gérer spécifiquement les violations de contrainte d'intégrité
                        echo "<script> alert(\"Erreur : Matricule de l'étudiant déjà utilisé.\"); </script>";
                    } else {
                        // Gérer les autres erreurs de base de données
                        $message = "Erreur de base de données : " . $e->getMessage();
                    }
                }
            }

            // Create a new PDO instance
            $pdo = new PDO($dsn, $user, $pass, $opt);
            // Prepare a SELECT statement to fetch all classes from the database
            $stmt = $pdo->prepare("SELECT Code_cl FROM CLASSE");
            // Execute the statement
            $stmt->execute();
            // Fetch all classes
            $classes = $stmt->fetchAll();

            // Close the statement and the database connection
            $stmt = null;
            $pdo = null;

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
            <h2>Ajouter un Etudiant</h2>
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
                    <label for="Date_naiss">Date de Naissance:</label>
                    <input type="text" id="Date_naiss" name="date_naiss" placeholder="YYYY-MM-DD" required>
                </div>
                <div class="form-group">
                    <label for="Lieu_naiss">Lieu de Naissance:</label>
                    <input type="text" id="Lieu_naiss" name="lieu_naiss" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <!-- Icône d'œil -->
                    <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer; display: initial;" onclick="togglePassword()"></i>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label for="c_cl">Code de la classe:</label>
                    <select id="c_cl" name="c_cl" required>
                      <option value="">Choisir une classe</option>
                      <?php foreach ($classes as $class): ?>
                          <option value="<?php echo htmlspecialchars($class['Code_cl']); ?>">
                              <?php echo htmlspecialchars($class['Code_cl']); ?>
                          </option>
                      <?php endforeach; ?>
                    </select>
                    
                </div>
                <a href="/be_web/classes/add_classe.php" style="text-decoration: none;">Ajouter une nouvelle classe </a>

                <div class="form-group" style="margin-top: 15px;">
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
