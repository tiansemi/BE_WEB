<?php
session_start();

if (isset($_SESSION['user']) && isset($_SESSION['utype']) && $_SESSION['utype'] == 'Etudiant') {
    require_once '../../dbconfig.php';
    require_once '../../TCPDF-main/tcpdf.php';

    $dbuser = $_SESSION['user'];
    $utype = $_SESSION['utype'];
    $mat = $dbuser['Matricule'];
    $moyenne = $_GET['moy'];
    $effectif = $_GET['eff'];
    $rang = $_GET['rang'];

    try {
        // Utiliser les variables de connexion à la base de données définies dans dbconfig.php
        $pdo = new PDO($dsn, $user, $pass, $opt);

        // Fetching student information
        $stmt = $pdo->prepare("SELECT * 
                            FROM ETUDIANT, CLASSE, FILIERE 
                            WHERE FILIERE.Code_fil=CLASSE.c_fil
                            AND CLASSE.Code_cl=ETUDIANT.c_cl
                            AND Matricule = :mat
                            ");
        $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
        $stmt->execute();
        $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetching courses and notes
        $stmt = $pdo->prepare("SELECT COURS.Intitule, SUIVRE.Notes, COURS.Coefficient 
                               FROM SUIVRE 
                               JOIN COURS ON COURS.Code_cours = SUIVRE.Code_cours
                               WHERE SUIVRE.Matricule = :mat");
        $stmt->bindParam(':mat', $mat, PDO::PARAM_INT);
        $stmt->execute();
        $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize PDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EPT');
        $pdf->SetTitle('Bulletin de Notes');

        // Add student information
        //$html = '<h1>EPT</h1>';
        $html = '<h1>Bulletin de Notes</h1>';
        $html .= '<h3>Matricule: ' . htmlspecialchars($etudiant['Matricule']) . '</h3>';
        $html .= '<h3>Nom: ' . htmlspecialchars($etudiant['Nom']) . '</h3>';
        $html .= '<h3>Prénoms : ' . htmlspecialchars($etudiant['Prenom']) . '</h3>';
        $html .= '<h3>Filière: ' . htmlspecialchars($etudiant['Libelle_fil']) . '</h3>';
        $html .= '<h3>Classe: ' . htmlspecialchars($etudiant['Libelle']) . '</h3>';

        // Add table of courses and notes
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr style="background-color:cadetblue">
                            <th>Cours</th>
                            <th>Note</th>
                            <th>Coefficient</th>
                            <th>Note final</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($cours as $course) {
            $note_finale = htmlspecialchars($course['Notes']) * htmlspecialchars($course['Coefficient']);
            $html .= '<tr>
                        <td>' . htmlspecialchars($course['Intitule']) . '</td>
                        <td>' . htmlspecialchars($course['Notes']) . '</td>
                        <td>' . htmlspecialchars($course['Coefficient']) . '</td>
                        <td>' . $note_finale . '</td>
                      </tr>';
        }
        $html .= '<div style="display: flex;justify-content: end;">
                    <h3>Moyenne générale : '.$moyenne.' </h3>
                    <h3>Effectif : '.$effectif.' </h3>
                    <h3>Rang : '.$rang.' </h3>
                </div>';
        $html .= '</tbody></table>';

        // Output PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('bulletin_notes.pdf', 'I');

    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit();
    }
} else {
    header('Location: ../error.html');
    exit();
}
?>