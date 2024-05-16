<?php
    session_start();
    $user = $_SESSION['user'];
    $utype = $_SESSION['utype'];

    if(!isset($_POST['logout']) ){
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="/be_web/">EPT Manager</a>
        <div class="d-flex">
            <div class="dropdown mr-1 me-2">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <?php echo $user['Nom']; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a style="color: black; text-decoration: none;" href="#">Profile</a></li>
                    <li><a  style="color: black; text-decoration: none;" href="#">Settings</a></li>
                </ul>
            </div>
            <form method="POST" action="./test.php" class="me-2">
                <button type="submit" class="btn btn-danger" name="logout">Logout</button>
            </form>
        </div>
    </nav>
    <script type="text/javascript" src="bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
    }else{
        if (isset($_SESSION['user'])) {
            // Unset all of the session variables
            $_SESSION = array();

            // If it's desired to kill the session, also delete the session cookie.
            // Note: This will destroy the session, and not just the session data!
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finally, destroy the session.
            session_destroy();
        }

        // Redirect to the index page
        header('Location: /be_web/index.php');
        exit();
    }
 ?>