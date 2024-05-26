<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <script type="text/javascript" src="bootstrap.bundle.min.js"></script>
</head>
<body>
<!-- Header Logout -->
<?php require_once 'header.php'; ?>
<!-- Dashboards -->
<?php
    if (isset($_SESSION['user']) && isset($_SESSION['utype'])) {
        
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
                            <button class="btn btn-warning rounded-pill mx-3 my-5 p-5" type="button">Filières</button>
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
                                    <a href="etudiants/">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les etudiants de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMatEtudiants" id="ajouterLinkEtudiants">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEtudiants" style="display: none;">
                                        <form action="etudiants/show_etudiant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Matricule de l'étudiant">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="etudiants/ajouter/">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un étudiant à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMatEtudiants2" id="ajouterLinkEtudiants2">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEtudiants2" style="display: none;">
                                        <form action="etudiants/modif_etudiant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Matricule de l'étudiant">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMatEtudiants3" id="ajouterLinkEtudiants3">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEtudiants3" style="display: none;">
                                        <form action="etudiants/del_etudiant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Matricule de l'étudiant">
                                        <input type="submit" hidden></form>
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
                                    <a href="enseignants/">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les enseignants de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMatEnseignant" id="ajouterLinkEnseignant">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un enseignant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEnseignant" style="display: none;">
                                        <form action="enseignants/show_enseignant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code de l'enseignant'">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="enseignants/add_enseignant.php">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un étudiant à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMatEnseignant2" id="ajouterLinkEnseignant2">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un étudiant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEnseignant2" style="display: none;">
                                        <form action="enseignants/modif_enseignant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code de l'enseignant'">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMatEnseignant3" id="ajouterLinkEnseignant3">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer un enseignant spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatEnseignant3" style="display: none;">
                                        <form action="enseignants/del_enseignant.php"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code de l'enseignant'">
                                        <input type="submit" hidden></form>
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
                                    <a href="cours/">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les cours de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat" id="ajouterLinkCours">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher un cours spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatCours" style="display: none;">
                                        <form action="cours/"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code du cours">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/ajouter/">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter un cours à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/modifier/">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'un cours spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="cours/supprimer/">
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
                            <button class="btn btn-warning rounded-pill mx-3 my-5 p-5" type="button" data-bs-toggle="modal" data-bs-target="#myModalFilieres">Filières</button>
                        </div>
                        <!-- The Modal -->
                        <div class="modal" id="myModalFilieres">
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
                                        #myModalFilieres a{
                                            color: black;
                                            text-decoration: none;
                                        }
                                        #myModalFilieres a:hover{ color: #ffc107;}
                                    </style>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="filieres/">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les filières de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#inputMat"></use></svg>
                                    <a href="#inputMat" id="ajouterLinkFilieres">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher une filière spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatFilieres" style="display: none;">
                                        <form action="filieres/"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code de la filière">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="filieres/ajouter/">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter une filière à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="filieres/modifier/">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'une filière spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="filieres/supprimer/">
                                        <div>
                                          <h4 class="mb-0">Supprimer</h4>
                                          Supprimer une filière spécifique de la base de donnée.
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
                                    <a href="classes/">
                                        <div>
                                          <h4 class="mb-0">AfficherTous</h4>
                                          Afficher tous les classes de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="#inputMat"  id="ajouterLinkClasse">
                                        <div>
                                          <h4 class="mb-0">AfficherUn</h4>
                                          Afficher une classe spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li id="inputMatClasse" style="display: none;">
                                        <form action="classe/"><input type="text" name="inputMat" style="top: -17px; padding: 10px; margin: 8px 0 8px 70px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; position: relative;" placeholder="insérer le Code de la classe">
                                        <input type="submit" hidden></form>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/ajouter/">
                                        <div>
                                          <h4 class="mb-0">Ajouter</h4>
                                          Ajouter une classe à la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/modifier/">
                                        <div>
                                          <h4 class="mb-0">Modifier</h4>
                                          Modifier les informations d'une classe spécifique de la base de donnée.
                                        </div>
                                    </a>
                                  </li>
                                  <li class="d-flex gap-4">
                                    <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#"></use></svg>
                                    <a href="classes/supprimer/">
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
        header('Location: /be_web/');
        exit();
    }
?>
</body>
<script>
    document.getElementById('ajouterLinkEtudiants').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEtudiants = document.getElementById('inputMatEtudiants');
        if (inputMatEtudiants.style.display === 'none' || inputMatEtudiants.style.display === '') {
            inputMatEtudiants.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEtudiants.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkEtudiants2').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEtudiants = document.getElementById('inputMatEtudiants2');
        if (inputMatEtudiants.style.display === 'none' || inputMatEtudiants.style.display === '') {
            inputMatEtudiants.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEtudiants.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkEtudiants3').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEtudiants = document.getElementById('inputMatEtudiants3');
        if (inputMatEtudiants.style.display === 'none' || inputMatEtudiants.style.display === '') {
            inputMatEtudiants.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEtudiants.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkEnseignant').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEnseignant = document.getElementById('inputMatEnseignant');
        if (inputMatEnseignant.style.display === 'none' || inputMatEnseignant.style.display === '') {
            inputMatEnseignant.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEnseignant.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkEnseignant2').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEnseignant = document.getElementById('inputMatEnseignant2');
        if (inputMatEnseignant.style.display === 'none' || inputMatEnseignant.style.display === '') {
            inputMatEnseignant.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEnseignant.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkEnseignant3').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatEnseignant = document.getElementById('inputMatEnseignant3');
        if (inputMatEnseignant.style.display === 'none' || inputMatEnseignant.style.display === '') {
            inputMatEnseignant.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatEnseignant.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkCours').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatCours = document.getElementById('inputMatCours');
        if (inputMatCours.style.display === 'none' || inputMatCours.style.display === '') {
            inputMatCours.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatCours.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkFilieres').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatFilieres = document.getElementById('inputMatFilieres');
        if (inputMatFilieres.style.display === 'none' || inputMatFilieres.style.display === '') {
            inputMatFilieres.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatFilieres.style.display = 'none'; // Hide the inputMat element
        }
    });
    document.getElementById('ajouterLinkClasse').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating to the href
        var inputMatClasse = document.getElementById('inputMatClasse');
        if (inputMatClasse.style.display === 'none' || inputMatClasse.style.display === '') {
            inputMatClasse.style.display = 'block'; // Show the inputMat element
        } else {
            inputMatClasse.style.display = 'none'; // Hide the inputMat element
        }
    });
</script>
</html>
