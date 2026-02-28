<?php

session_start();
require_once 'GestionBDD/connexionBDD.php';

    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}

// Vous devez etre admin pour acceder a cette page
if ($_SESSION['grade'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Félicitation (ou pas...) ! L'utilisateur que vous avez choisi de supprimer, l'a bien été.">
    <title>Suppression d'un utilisateur</title>

    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="CSS/gestionUtilisateur/suppressionUtilisateur.css">

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
            <h2 class="titre1">Gestion des utilisateurs</h2>
        </div>

        <hr id="ligneHaute">

        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $email = $_POST['email'];
            $identifiant = $_POST['identifiant'];

        }


        try {


            $requete = "DELETE FROM utilisateur WHERE nom = :nom AND prenom = :prenom AND email = :email AND identifiant = :identifiant";
            $requeteComplete = $bdd -> prepare($requete);
            $requeteComplete -> execute([':nom' => $Nom, ':prenom' => $Prenom, ':email' => $email, ':identifiant' => $identifiant]);
                
                
                
                
                if ($requeteComplete->rowCount() > 0){ //Vérif si l'utilisateur a bien été supprimé

                    echo "<p class ='titre2' style='font-size: x-large;'>L'utilisateur a bien été supprimé !</p>";
                    ?><script src="./js/confetti.js"></script><?php


                    if (($_SESSION["nom"] == $Nom) && ($_SESSION["prenom"] == $Prenom) && ($_SESSION["email"] == $email) && ($_SESSION["identifiant"] == $identifiant) ){ //On vérif si l'utilisateur supprimé est l'utilisateut actuellement connecté

                        $_SESSION['connecte'] = FALSE; //Deconnexion auto
                        header("Location: connexion.php"); //Retour potail connexion
                        exit;



                    }

                }
                else { // Si commande n'a pas fonctionné

                    echo "<p class ='titre4'> Erreur </p>"; // En cas d'erreur dans la modification du fichier csv


                }


        }
        catch (PDOException $e){
            echo "Erreur". $e->getMessage();


        }

        ?>

        <div class = "bouton-position-supprimer">
            <button onclick="window.location.href='./gestionAdmin.php';"class="boutonSupprimer">Retour</button>
        </div>   
        <div class = "bouton-position-supprimer">
            <button onclick="window.location.href='./accueil.php';" class="boutonSupprimer" >Accueil</button>
        </div>
    </div>

</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>