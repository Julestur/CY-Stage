<?php

session_start();
    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Verifier bien que vous avez choisi la bonne personne... Cela pourrait être une erreur. Cette page est une double vérification de suppression du profil. En cas d'étourderie...">
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
            <h2 class="titre1">Suppression d'un utilisateur</h2>
        </div>

        <hr id="ligneHaute">
        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $email = $_POST['email'];
            $identifiant = $_POST['identifiant'];


            echo "<p class ='titre2' style='font-size: x-large;'>Voulez vous vraiment supprimer cet utilisateur ?</p> <br>";
        
        
            echo "<table class='tabInfo2'>";   

            // Création du tableau du profil à supprimer --------------------------------
            echo "<tr>";
            echo "<th>"."Nom"."</th>";
            echo "<th>"."Prenom"."</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>".$Nom."</td>";
            echo "<td>".$Prenom."</td>";
            echo "</tr>";
        
            echo "</table>";
            // ---------------------------------------------------------------------------

            echo "<br> <p class ='titre2'>CETTE SUPPRESSION EST DEFINITIVE !</p>"; // Préviens l'utilisateur de la fatalité de l'action (en cas de mauvaise manipulation)

            echo "<form action='./supprimerAdmin.php' method='post'>";

            echo "<input type='hidden' name='Nom' value='".$Nom."'>";
            echo "<input type='hidden' name='Prenom' value='".$Prenom."'>";
            echo "<input type='hidden' name='email' value='".$email."'>";
            echo "<input type='hidden' name='identifiant' value='".$identifiant."'>";


        }
        ?>





        <div class = "bouton-position-supprimer">
                                
            <input class="boutonSupprimer" type="submit" value="Supprimer l'admin">
        </div>
        </form>
        <div class = "bouton-position-supprimer">

            <button  onclick="window.location.href='./gestionAdmin.php';" class="boutonSupprimer">Retour</button>
        
        </div>
    </div>

</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>