<?php
session_start();
    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}

//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != 'vide') 
{
    header("Location: changerMDP_temp.php");
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
    <meta name="description" content="Supprimez et surveillez les administrateurs du site en un clic.">
    <title>Gestion des utilisateurs</title>

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

        // Affichage avec la BDD -----------------------------------------------------------------------------------------
        //-------------------------------------------------------------------------------------------------------------------

        require_once 'GestionBDD/connexionBDD.php';
        $bdd->exec("USE dataStage");



        try {

            $requete = $bdd -> query("SELECT u.*, s.libelle FROM utilisateur u 
                                      LEFT JOIN statut s ON u.idStatut = s.idStatut");


$nb = $requete->rowCount();
echo ""; 
if ($nb === 0) {
    echo "<p style='color:red; text-align:center;'>La requête ne trouve aucun utilisateur dans la table.</p>";
}


        echo "<div class = contenu>";

        echo "<table class='tabInfo'>";   // Création du tableau 


        // Affichage des en-têtes ----------------------------------------------------------------
        echo "<thead>";
        echo "<tr>";
            
        echo "<th>"."Nom"."</th>";
        echo "<th>"."Prenom"."</th>";
        echo "<th>"."Mail"."</th>";
        echo "<th>"."Pseudo"."</th>";
        echo "<th>"."Profil"."</th>";
        echo "<th>"."Grade"."</th>";
        echo "<th>"." "."</th>";

        echo "</tr>";
        echo "</thead>";

        //-----------------------------------------------------------------------------------------


        foreach ($requete as $info){


        // Affichage des lignes 
                echo "<tr>";
            
            
                echo "<td>".$info['nom']."</td>";
                echo "<td>".$info['prenom']."</td>";
                echo "<td>".$info['email']."</td>";
                echo "<td>".$info['identifiant']."</td>";
                echo "<td><img id='image-profil' src='images_profil/".$info['pdp']."'></td>";
                echo "<td>".$info['libelle']."</td>";

                
                echo "<td>";
                echo "<form action='./verifSuprimerAdmin.php' method='post'>";

                echo "<input type='hidden' name='Nom' value='".$info['nom']."'>";
                echo "<input type='hidden' name='Prenom' value='".$info['prenom']."'>";
                echo "<input type='hidden' name='email' value='".$info['email']."'>";
                echo "<input type='hidden' name='identifiant' value='".$info['identifiant']."'>";


                ?>

                <div class = "bouton-position-supprimer">
                                
                <input class="boutonSupprimer" type="submit" value="Supprimer">
                            
                </div>



            <?php
            echo "</form>";

            echo "</td>";

            echo "</tr>";
        }
            
        echo "</table>";
        echo "</div>";

        } catch (PDOException $e){
    
            echo "Erreur :" . $e->getMessage();

        }


        ?>


    </div>

</div>

    <script src="js/iconOnglet.js"></script>

</body>
</html>


























