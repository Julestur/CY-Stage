



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


    $erreur_entreprise = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'])){

        try {

                //On recupère les mail et id 
                $requete = "SELECT idEntreprise FROM entreprise
                            WHERE nom = :nom";
                
                $execution = $bdd->prepare($requete);
                $execution->execute([':nom' => $_POST['nom']]);

                $res = $execution->fetch(PDO::FETCH_ASSOC);

                // Verif si id et mail non utilisé
                if ($res){

                    $erreur_entreprise = "Cette entreprise existe déjà !";
                
                }


                //Redirection vers la page pour ajouter à la BDD
                else {
            
                    $_SESSION['form_ajout'] = [ 'nom' => $_POST['nom']];
                    header("Location: enregistrerEntreprise.php");
                    exit;

                }
                

        }
        catch (PDOException $e){
                echo "Erreur". $e->getMessage();

        }

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
            <h2 class="titre1">Ajout d'une entreprise</h2>
        </div>

        <hr id="ligneHaute">

            <form action="" method="POST" class="portail">

            <!-- ajout d'une balise de paragraphe si une erreur est détectée  -->
                <?php if (!empty($erreur_entreprise)): ?>
                    <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                        <?= $erreur_entreprise ?>
                    </p>
                <?php endif; ?>
                
            
            
                <input type="text" placeholder="Nom de l'entreprise" class="contenu-portail" name="nom" required>
                <hr>
                
            
                <div class="bouton"><input type="submit" value="Inscription" class="boutonAjout"></div>
            </form>
    </div>

</div>

    <script src="js/iconOnglet.js"></script>
    
    
    
</body>
</html>