<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <script type="text/javascript" src="bootstrap.bundle.min.js"></script>
</head>

<?php
    session_start();
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        $user = $_SESSION['user'];
        $utype = $_SESSION['utype'];

        if ($utype == 'Etudiant') {
        ?>
            <body>
                <div class="d-flex gap-2 justify-content-center py-5">
                    <div class="row mb-3 text-center">
                        <div class="col-sm-4 themed-grid-col">
                            <button class="btn btn-primary rounded-pill mx-3 my-5 p-5" type="button">Profil</button>
                        </div>

                        <div class="col-sm-4 themed-grid-col">
                            <button class="btn btn-success rounded-pill mx-3 my-5 p-5" type="button">Cours</button>
                        </div>

                        <div class="col-sm-4 themed-grid-col">
                            <button class="btn btn-warning rounded-pill mx-3 my-5 p-5" type="button">Notes</button>
                        </div>
                    </div>
                </div>
            </body>
        <?php 
        }

        if ($utype == 'Enseignant') {
            if ($user['status']=='ad') {
                // Dashboard de l'admin
        ?>
            <body>
                <style type="text/css">
                    .container button{
                        width: 185px;
                    }
                </style>
                <div class="container gap-2 justify-content-center py-5">
                    <div class="row mb-3 text-center">
                        <div class="col-md-4 themed-grid-col">
                            <button class="btn btn-primary rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalEtudiants">Etudiants</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalEtudiants">
                          <div class="modal-dialog" role="document" style="text-align: initial;">
                            <div class="modal-content rounded-4 shadow">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="width: 1em;"></button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                                <ul class="d-grid gap-4 my-5 list-unstyled small">
                                    <style>
                                        #myModalEtudiants a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalEtudiants a:hover{ color: #0d6efd;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="etudiants/index.php">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les etudiants de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="etudiants/ajouter/index.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un étudiant à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="etudiants/modifier/index.php">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="etudiants/supprimer/index.php">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 themed-grid-col">
                            <button class="btn btn-secondary rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalEnseignants">Enseignants</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalEnseignants">
                          <div class="modal-dialog" role="document" style="text-align: initial;">
                            <div class="modal-content rounded-4 shadow">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="width: 1em;"></button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                                <ul class="d-grid gap-4 my-5 list-unstyled small">
                                    <style>
                                        #myModalEnseignants a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalEnseignants a:hover{ color: #5c636a;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="enseignants/index.php">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les enseignants de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="enseignants/ajouter/index.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un étudiant à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="enseignants/modifier/index.php">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="enseignants/supprimer/index.php">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer un enseignant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 themed-grid-col">
                            <button class="btn btn-success rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalCours">Cours</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalCours">
                          <div class="modal-dialog" role="document" style="text-align: initial;">
                            <div class="modal-content rounded-4 shadow">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="width: 1em;"></button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                                <ul class="d-grid gap-4 my-5 list-unstyled small">
                                    <style>
                                        #myModalCours a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalCours a:hover{ color: #157347;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/index.php">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les cours de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un cours spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/ajouter/index.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un cours à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/modifier/index.php">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un cours spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/supprimer/index.php">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer un cours spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row mb-3 text-center">
                        <div class="col-md-4 themed-grid-col">
                            <button class="btn btn-warning rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalNotes">Notes</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalNotes">
                          <div class="modal-dialog" role="document" style="text-align: initial;">
                            <div class="modal-content rounded-4 shadow">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="width: 1em;"></button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                                <ul class="d-grid gap-4 my-5 list-unstyled small">
                                    <style>
                                        #myModalNotes a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalNotes a:hover{ color: #ffc107;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="notes/index.php">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les notes de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher une note spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="notes/ajouter/index.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter une note à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="notes/modifier/index.php">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'une note spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="notes/supprimer/index.php">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer une note spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 themed-grid-col">
                            <button class="btn btn-info rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalClasses">Classe</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalClasses">
                          <div class="modal-dialog" role="document" style="text-align: initial;">
                            <div class="modal-content rounded-4 shadow">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" style="width: 1em;"></button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                                <ul class="d-grid gap-4 my-5 list-unstyled small">
                                    <style>
                                        #myModalClasses a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalClasses a:hover{ color: #0dcaf0;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/index.php">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les classes de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher une classe spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/ajouter/index.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter une classe à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/modifier/index.php">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'une classe spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/supprimer/index.php">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer une classe spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </body>
        <?php 
            }else{ // Dashboard d'un enseignant

            }
        }
    }else{
        header('Location: index.php');
        exit();
    }
?>

<?php
    $host = 'localhost';
    $db   = 'be_web';
    $user = 'tiansemi';
    $pass = '*ù$ù*ù$ù';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);

    
?>
</html>
