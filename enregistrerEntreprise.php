<?php
    session_start();

    if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
    {
        header("Location: connexion.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Félicitation ! Vous êtes officiellement administrateur de la gestion des stages de CYTECH !">
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
        <?php

        // Ajout dans la BDD
        require_once 'GestionBDD/connexionBDD.php';


        if (!isset($_SESSION['form_ajout'])) {
            header("Location: ajoutStage.php");
            exit;
        }

        $form = $_SESSION['form_ajout'];
        $nom = $form["nom"];

     




    

        try {

            $requete = "INSERT INTO entreprise(nom)
                        VALUES (:nom)";
            
            $requeteComplete = $bdd -> prepare($requete);

            $requeteComplete -> execute([':nom' => $nom]);


        }
        catch (PDOException $e){
            echo "Erreur". $e->getMessage();


        }


        ?>

        <!-- S'affiche si l'inscription c'est bien déroulée : Permet d'informer l'utilisateur de son inscription -->
        <div class="contenu-centre">
            <h4 class="titre2" style="font-size: xx-large;"> Inscription terminée !</h1>
            
            <p class="titre2"><?php echo "L'entreprise a bien été ajoutée !" ?></p>
            <script src="./js/confetti.js"></script>
            
            <div class = "bouton">
            <p><a href="accueil.php"><input type="button" value="Retour" class="boutonRetour"></a></p>
            </div>

        </div>
    </div>

</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>