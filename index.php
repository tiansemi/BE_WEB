<?php
    session_start();

    require_once 'dbconfig.php';
    $pdo = new PDO($dsn, $user, $pass, $opt);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = hash('sha512', $_POST["password"]); // Hash the password

        // Chercher dans la table 'ETUDIANT'
        $stmt = $pdo->prepare("SELECT * FROM ETUDIANT WHERE Matricule = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]); 
        $user = $stmt->fetch();
        $utype='Etudiant';

        if (!$user) {
            // Si l'utilisateur n'est pas trouvé dans 'ETUDIANT', chercher dans 'ENSEIGNANT'
            $stmt = $pdo->prepare("SELECT * FROM ENSEIGNANT WHERE Code_ens = :username AND password = :password");
            $stmt->execute(['username' => $username, 'password' => $password]); 
            $user = $stmt->fetch();
            $utype='Enseignant';
        }

        if ($user) {
            $_SESSION['user'] = $user; // Store user data in session
            $_SESSION['utype'] = $utype; // Store user type in session
            // Redirigez l'utilisateur vers la page d'accueil ou la page de tableau de bord ici
            header('Location: dashboard.php');
            exit();
        } else {
            echo "<script>document.addEventListener('DOMContentLoaded', function() {  document.getElementById('errorLogin').removeAttribute('hidden'); }); </script>";
            
        }
    }
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        header('Location: /be_web/dashboard.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EPT Manager</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <script type="text/javascript" src="bootstrap.bundle.min.js"></script>
</head>

<body>

	<section class="text-center" style="">
		<!-- Background image -->
        <div class="p-5 bg-image" style="
	        background-image: url('171.jpg');
	        height: 300px;
	        background-position: center;
	        background-size: cover;
	        "></div>
        <div class="d-flex flex-row">
            <div class="col-3"></div>
            <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary col-6 offset-3" style="margin-top: -100px;
		        backdrop-filter: blur(30px);
		        margin-left: 0 !important;
		        margin-right: 0 !important;">
                <div class="card-body py-5 px-md-5" style="">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-5">Connexion</h2>
                            <div id="errorLogin" class="pb-2" hidden style="color: red;">Identifiant ou mot de passe incorrect.</div>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                <!-- Email input -->
                                <div data-mdb-input-init="" class="form-outline mb-4">
                                    <input placeholder="Matricule" type="text" id="username" class="form-control text-center" name="username" required>
                                </div>
                                <!-- Password input -->
                                <div data-mdb-input-init="" class="form-outline mb-4">
                                    <input placeholder="Mot de passe" type="password" id="form3Example4" class="form-control text-center" id="password" name="password" required>
                                </div>
                                <!-- Submit button -->
                                <button type="submit" data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-primary btn-block mb-4">Se connecter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
