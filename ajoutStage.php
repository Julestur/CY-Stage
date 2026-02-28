



<?php
    session_start();

    require_once 'GestionBDD/connexionBDD.php';


    if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
        header("Location: connexion.php");
        exit;
    }

    // Vous devez etre admin ou une entreprise pour acceder a cette page
    if ($_SESSION['grade'] !== 'admin' && $_SESSION['grade'] !== 'entreprise') {
        header("Location: connexion.php");
        exit;
    }


//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != 'vide') 
{
    header("Location: changerMDP_temp.php");
    exit;
}




    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $_SESSION['form_ajout'] = [ 'intitule' => $_POST['intitule'],
                                    'detail' => $_POST['detail'],
                                    'dateDebut' => $_POST['dateDebut'],
                                    'dateFin' => $_POST['dateFin'],
                                    'entreprise' => $_POST['entreprise']
        ];
        header("Location: enregistrerStage.php");
        exit;

    }
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ajouter facilement de nouveaux utilisateurs sur le site pour permettre au plus grand nombre d'organiser les sorties samedi neige.">
    <title>Inscription</title>

    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="CSS/gestionUtilisateur/ajoutUtilisateur.css">


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <?php include("webappLinks.php"); ?>
</head>
<body>
<!-- Header------------------------------------------------------------------------------------------------------ -->
<?php require('header.php'); ?>

<!-- Content------------------------------------------------------------------------------------------------------ -->
<div class="page-dimension">
    <div class="contenu-principal"> 
        <div class="accueil">  
            <h2 class="titre1">Ajout d'une offre de stage</h2>
        </div>

        <hr id="ligneHaute">

            <form action="" method="POST" class="portail">
                
            
            
                <input type="text" placeholder="Intitulé" class="contenu-portail" name="intitule" required>
                <hr>
                <input type="text" placeholder="Description du stage" class="contenu-portail" name="detail"  required>
                <hr>
                <input type="date" placeholder="Date Début" class="contenu-portail" name="dateDebut" required>
                <hr>
                <input type="date" placeholder="Date Fin" class="contenu-portail" name="dateFin"  required>
                <hr>
                <input type="text" placeholder="Entreprise" class="contenu-portail" name="entreprise" required>
                <hr>
                
            
                <div class="bouton"><input type="submit" value="Inscription" class="boutonAjout"></div>
            </form>
    </div>

</div>

    <script src="js/iconOnglet.js"></script>
    
    
    
</body>
</html>