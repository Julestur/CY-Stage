<?php
session_start();


    
if(isset($_SESSION['connecte']) && $_SESSION['connecte'] == TRUE) 
{
    header("Location: accueil.php");
    exit;
}
require_once 'GestionBDD/creationBDD.php';
require_once 'GestionBDD/connexionBDD.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connectez vous sur le site de gestion des stages de CYTECH grâce à votre compte CYTECH. Si vous n'avez pas de compte, demandez à votre administrateur.">
    
    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="styleconnexion.css">
    <title>Connexion</title>

    <?php include("webappLinks.php"); ?>

    <!-- ION-ICONs -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<!-- Header----------------------------------------------------------------------------------------------------- -->
<div id="logoCY">
    <img id="logoCY" src="../Images/logo2.png" alt="logo de cytech">
</div>
<h3 id="titre">PORTAIL GESTION DES STAGES</h3>

<!-- Content------------------------------------------------------------------------------------------------------ -->
<form action="auth.php" method="post" class="portail">
    <input type="text" placeholder="Identifiant ou adresse e-mail" class="contenu-portail" style="font-size:20px;" name="pseudo" required>
    <hr>
    <input type="password" placeholder="Mot de passe" class="contenu-portail" style="font-size:20px;" name="mot-de-passe" required>
    <hr>

    <?php
    if(isset($_GET["error"])) // Si le mot de passe est incorrect, un message apparait pour signaler l'utilisateur
    {
        echo('<p id="erreur"><ion-icon name="close-outline"></ion-icon> Identifiant ou mot de passe incorrect <ion-icon name="close-outline"></ion-icon></p> ');
    }
    ?>

    <div class="bouton">
        <input type="submit" value="Connexion" id="bouton-style-connexion">
    </div> 
</form>

<div class="info">
    <a href="MotDePasseOublie.php">Mot de passe oublié ?</a>

</div>
<div class="info">
    <a href="incriptionPortail.php"> Pas encore de compte ?</a>

</div>
<div class="vide"></div> <!-- Permet d'afficher le footer en bas d'une page pour n'immporte quel format et résolution -->

<!-- Footer------------------------------------------------------------------------------------------------------ -->
<footer class="footer">
    <p>&copy; <script>document.write(new Date().getFullYear())</script>, CYTECH</p>
    <p id="footerLogo"><img id="logo" src="../Images/LogoCyBlanc.png" alt="logo sortie samedi neige"></p>
</footer>

    
<script src="js/iconOnglet.js"></script>



</body>
</html>