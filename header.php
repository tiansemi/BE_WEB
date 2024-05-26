<?php
    session_start();
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];
    }else{
        header('Location: /be_web/');
        exit();
    }

    if(!isset($_POST['logout']) && isset($user)){
?>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand ms-2" href="/be_web/">EPT Manager</a>
        <div class="d-flex">
            <div class="me-2">
                <button type="button" class="btn btn-primary">
                    <?php echo $user['Nom']; ?>
                </button>
            </div>
            <form method="POST" action="/be_web/header.php" class="me-2" style="padding: 0;">
                <button type="submit" class="btn btn-danger" name="logout">Logout</button>
            </form>
        </div>
    </nav>
<?php 
    }elseif (isset($_POST['logout']) && isset($user)){
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