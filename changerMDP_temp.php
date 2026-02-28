<?php
session_start();

require_once 'GestionBDD/connexionBDD.php';

   
$MDP_change = $_SESSION['mdp_tmp'];

if (isset($_POST['mot-de-passe'])) {
    $nouveau_mdp = $_POST['mot-de-passe'];
    $confirme_mdp = $_POST['confirmation-mot-de-passe'];
    $pseudo_session = $_SESSION['pseudo'];


    
               
                
    try{
            
            if ($confirme_mdp != $nouveau_mdp) {
                    $confirme_mdp_faux = 'La confirmation du mot de passe est incorrecte.';
                } 
            else {

                $requete = "SELECT mdp FROM utilisateur WHERE identifiant=:id OR email=:id";
                $requeteComplete = $bdd -> prepare($requete);
                $requeteComplete -> execute([':id' => $pseudo_session]);

                $info = $requeteComplete->fetch(PDO::FETCH_ASSOC);
                        
                if ($info){

                    $ancienMDP = $info['mdp'];

                }
                else {
                    echo "Utilisateur non trouvé";
                }


                if ($nouveau_mdp != $ancienMDP) {

                    //MAJ de la BDD-----------------------------------------------------------------------------------------------------
                    $requete = "UPDATE utilisateur SET mdp = :nouveau_MDP
                                WHERE email = :id OR identifiant = :id";
                    $requeteComplete = $bdd -> prepare($requete);
                    $requeteComplete -> execute([':nouveau_MDP' => $nouveau_mdp, ':id' => $pseudo_session]);
                        
                    $requete2 = "UPDATE utilisateur SET mdp_tmp = :nouveau_MDP
                                WHERE email = :id OR identifiant = :id";
                    $requeteComplete2 = $bdd -> prepare($requete2);
                    $requeteComplete2 -> execute([':nouveau_MDP' => 'vide', ':id' => $pseudo_session]);
                        
                    $_SESSION['mdp_tmp'] = "vide";
                                        
                
                    $mot_de_passe_change = 'Mot de passe changé avec succès ! Cliquez sur retour !';
                } 
                else {
                    $mdp_deja_utilise = "Le nouveau mot de passe est identique à l'ancien.";
                }
            }
    }catch (PDOException $e){
            echo "Erreur". $e->getMessage();
    }
}
           
           
           

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Changer votre mot de passe rapidement sur cette page en cas d'oublie. Attention ! Votre ancien mot de passe sera demandé.">
    <title>Changer Mot De Passe</title>

    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="accueilStyle.css">

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
            <h2 class="titre1">Changement de mot de passe</h2>
        </div>

        <hr id="redline-mdp">

        <h3 class="titre1">Une demande de réinitialisation de mot de passe a été effectuée. Veuillez le modifier <b><u>IMMÉDIATEMENT</u></b>.</h3>
        <h3 class="titre3">(Pour plus sécurité, les autres fonctionnalités seront à nouveau disponibles après la modification du mot de passe.)</h3>

        <form action="./changerMDP_temp.php" method="post" class="portail">

            <input type="password" placeholder="Nouveau Mot De Passe" class="contenu-portail" style="font-size:20px;" name="mot-de-passe" required>
            <hr>
            <input type="password" placeholder="Confirmation Mot De Passe" class="contenu-portail" style="font-size:20px;" name="confirmation-mot-de-passe" required>
            <hr>
            <!-- ajout d'une balise de paragraphe si une des 4 erreurs possibles dans le php est détectée  -->
            <?php if (!empty($confirme_mdp_faux)): ?>
                <p style="margin-top: 10px; text-align: center; color: orange; font-size: 20px; font-family: helvetica;">
                    <?= $confirme_mdp_faux ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($mot_de_passe_change)): ?>
                <p style="margin-top: 10px; text-align: center; color: green; font-size: 20px; font-family: helvetica;">
                    <?= $mot_de_passe_change ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($mdp_deja_utilise)): ?>
                <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                    <?= $mdp_deja_utilise ?>
                </p>
            <?php endif; ?>
            <div class="bouton"><input type="submit" value="Enregistrer" id="bouton-style-connexion"></div>
            <div class="info" style="padding-top: 4%; font-size: x-large;"><a href="accueil.php">Retour <<<</a></div>

        </form>
    </div>
    <h4 class="titre4">Si cette demande ne vient pas de vous, contactez un administrateur.</h4>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>