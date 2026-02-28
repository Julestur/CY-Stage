


<?php
    session_start();

    require_once 'GestionBDD/connexionBDD.php';

    //Import des classes depuis PHPmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    if(isset($_SESSION['connecte']) && $_SESSION['connecte'] == TRUE) 
    {
        header("Location: accueil.php");
        exit;
    }









        


















    $erreur_mail = "";
    $erreur_mdp = "";
    $nom = "";
    $prenom = "";
    $pseudo = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $mdp = $_POST['mot-de-passe'] ?? '';
        $confirme = $_POST['confirmation-mot-de-passe'] ?? '';
        $nomEntreprise = $_POST['nom_entreprise'] ?? '';

        
    
        // Vérif si le mail ou l'id n'est pas déjà utilisé
        try {

            //On recupère les mail et id 
            $requete = "SELECT email,identifiant FROM utilisateur
                        WHERE email = :mail OR identifiant = :pseudo";
            
            $execution = $bdd->prepare($requete);
            $execution->execute([':mail' => $email,':pseudo' => $pseudo]);

            $res = $execution->fetch(PDO::FETCH_ASSOC);

            // Verif si id et mail non utilisé
            if ($res){
                if($res['email'] === $email){
                    $erreur_mail = "Cette adresse mail est déjà inscrite";
                }
                else if ($res['identifiant'] === $pseudo){
                    $erreur_mail = "Cet identifiant  est déjà utilisé";
                }
            }

            //Verif la confirmation du mdp
            else if ($mdp != $confirme){
                $erreur_mdp = "La confirmation du mot de passe est incorrecte";
            }

            // Si aucune erreur on envoie le mdp de confirmation
            else if(empty($erreur_mail) && empty($erreur_mdp)){





                    //##############################################################################################
                    //Envoi de ce mdp de confirmation par mail à l'utilisateur    
                    //##############################################################################################


                    require 'PHPmailer/PHPMailer.php';
                    require 'PHPmailer/SMTP.php';
                    require 'PHPmailer/Exception.php';

                    //Création d'un mail
                    $mail = new PHPMailer(true);

                    //Création d'un nouveau mdp temporaire
                    $codeVerif = rand(100000,999999);
                    $_SESSION['form_inscription'] = $_POST;
                    $_SESSION['codeVerif'] = $codeVerif;

                    try {
                        //Paramétrage du mail
                        $mail -> isSMTP();
                        $mail -> Host = 'smtp.gmail.com';
                        $mail -> SMTPAuth = true;
                        $mail -> Username = 'ne.pas.repondre.alb64@gmail.com'; //Adresse Gmail du compte google
                        $mail -> Password = 'fsle kzrz osyv ojtv'; //MDP de l'application du compte google
                        $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail -> Port = 587;
                        $mail -> CharSet = 'UTF-8';

                        //Expéditeur + destinataire
                        $mail -> setFrom('ne.pas.repondre.alb64@gmail.com', 'CYStage');
                        $mail -> addAddress($email,'ID');

                        //Création du contenue du mail
                        $mail -> isHTML(true);
                        $mail -> Subject = 'Confirmation de votre adresse e-mail';
                        $mail -> Body = 'Bonjour,<br><br>Voici votre code temporaire: <b>'.$codeVerif .'</b><br><br>Ce code est à usage unique, veuillez modifier votre mot passe dès que vous vous connectez. <b>Ne le partagez avec personne.</b><br>';
                        $mail -> Body .= 'Ce code sera considéré comme votre ancien mot de passe lors de son changement sur le site.<br>';
                        $mail -> Body .= '<b>FAITES LE CHANGEMENT DE MOT DE PASSE IMMÉDIATEMENT APRÈS AVOIR REÇU CE MAIL !</b><br><br><br>';
                        $mail -> Body .= "Merci,<br><b>";
                        $mail -> AltBody = 'Bonjour, Voici votre le code temporaire: '.$codeVerif;

                        $mail -> send(); //Envoi

                        //Création d'un message pour valider l'envoi
                        $confirmation_mail = 'Le message a bien été envoyé.';
                        $_SESSION['confirmation'] = $confirmation_mail;
                        $est_envoye = TRUE;
                        
                        header('Location: incriptionPortail2.php');
                        exit;


                    } catch (Exception $e) { //Si un problème survient
                        $erreur_mail = "Mail invalide. Veuillez entrer le mail associé au compte.";
                        $_SESSION['erreur'] = $erreur_mail;
                    } 


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
    <meta name="description" content="Connectez vous sur le site de gestion des stages de CYTECH grâce à votre compte CYTECH. Si vous n'avez pas de compte, demandez à votre administrateur.">
    
    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="styleconnexion.css">
    <title>Connexion</title>

    <?php include("webappLinks.php"); ?>

</head>
<body>
<!-- Header----------------------------------------------------------------------------------------------------- -->
<div id="logoCY">
    <img id="logoCY" src="../Images/logo2.png" alt="logo de cytech">
</div>
<h3 id="titre">PORTAIL GESTION DES STAGES</h3>

<!-- Content------------------------------------------------------------------------------------------------------ -->


    <form action="" method="POST" class="portail">
                <!-- ajout d'une balise de paragraphe si une erreur sur le mail est détectée  -->
                <?php if (!empty($erreur_mail)): ?>
                    <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                        <?= $erreur_mail ?>
                    </p>
                <?php endif; ?>
                <!-- ajout d'une balise de paragraphe si une erreur sur la confirmation du mot de passe et/ou du mot de passe est détectée  -->
                <?php if (!empty($erreur_mdp)): ?>
                    <p style="margin-top: 10px; text-align: center; color: orange; font-size: 20px; font-family: helvetica;">
                        <?= $erreur_mdp ?>
                    </p>
                <?php endif; ?>
                <input type="text" placeholder="Nom" class="contenu-portail" style="font-size:20px;" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                <hr>
                <input type="text" placeholder="Prenom" class="contenu-portail" style="font-size:20px;" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>
                <hr>
                <input type="email" placeholder="Adresse e-mail" class="contenu-portail" style="font-size:20px;" name="email" required>
                <hr>
                <input type="text" placeholder="Identifiant" class="contenu-portail" style="font-size:20px;" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>" required>
                <hr>
                <input type="password" placeholder="Mot de passe" class="contenu-portail" style="font-size:20px;" name="mot-de-passe" required>
                <hr>
                <input type="password" placeholder="Confirmation mot de passe" class="contenu-portail" style="font-size:20px;" name="confirmation-mot-de-passe" required>
                <hr>
                <select name="grade" id = type_grade class="selection-administration style="font-size:20px;"">
                    <option value="" disabled selected>-- Choisissez un rôle --</option>
                    <option value="Etudiant">Etudiant</option>                  
                    <option value="Professeur">Professeur</option>
                    <option value="Entreprise">Entreprise</option>
                </select>
                <div id="bloc-nom-entreprise" style="display: none;" class="portail" style="font-size:20px;">
                    <input type="text" id="nom-entreprise" placeholder="Entrez le nom de l'entreprise" class="contenu-portail" name="nom_entreprise">

                </div>
                
                <div id="bloc_formation" style="display: none;" class="portail">

                    <select name="classe" id = formation class="selection-administration" style="font-size:20px;">
                        <option value="" disabled selected>-- Choisissez la classe --</option>
                        <option value="Pré ING1">Pré ING1</option>
                        <option value="Pré ING2">Pré ING2</option>                  
                        <option value="ING1 GM">ING1 GM</option>
                        <option value="ING1 GI">ING1 GI</option>
                        <option value="ING2 GM">ING2 GM</option>
                        <option value="ING2 GI">ING2 GI</option>
                        <option value="ING3 GM">ING3 GM</option>
                        <option value="ING3 GI">ING3 GI</option>
                    </select>

                </div>
                <hr>
                <div class="bouton">
                    <input type="submit" value="Inscription" id="bouton-style-connexion">
                </div> 
    </form>
    </div>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
</div>

    <script src="js/iconOnglet.js"></script>
    
    <script>

    // On récupère les éléments
    const selectGrade = document.getElementById('type_grade');
    const blocEntreprise = document.getElementById('bloc-nom-entreprise');
    const inputEntreprise = document.getElementById('nom-entreprise');
    const blocFormation = document.getElementById('bloc_formation');
    const inputFormation = document.getElementById('formation');

    // Fonction pour gérer l'affichage
    selectGrade.addEventListener('change', function() {
        if (this.value === 'Entreprise') {
            blocEntreprise.style.display = 'block';
            inputEntreprise.required = true; // On le rend obligatoire seulement si affiché
        } 
        else {
            blocEntreprise.style.display = 'none';
            inputEntreprise.required = false; // On retire l'obligation
            inputEntreprise.value = ''; // On vide le champ
        }

        if (this.value === 'Etudiant') {
            blocFormation.style.display = 'block';
            inputFormation.required = true; // On le rend obligatoire seulement si affiché
        }
        else {
            blocFormation.style.display = 'none';
            inputFormation.required = false; // On retire l'obligation
            inputFormation.value = ''; // On vide le champ
        }
    });





    
    </script>

















    <?php
    if(isset($_GET["error"])) // Si le mot de passe est incorrect, un message apparait pour signaler l'utilisateur
    {
        echo('<p id="erreur"><ion-icon name="close-outline"></ion-icon> Identifiant ou mot de passe incorrect <ion-icon name="close-outline"></ion-icon></p> ');
    }
    ?>

    

<div class="info">
    <a href="connexion.php">J'ai déjà un compte</a>
</div>
<div class="vide"></div> <!-- Permet d'afficher le footer en bas d'une page pour n'immporte quel format et résolution -->

<!-- Footer------------------------------------------------------------------------------------------------------ -->
<footer class="footer">
    <p>&copy; <script>document.write(new Date().getFullYear())</script>, CYTECH</p>
    <p id="footerLogo"><img id="logo" src="../Images/LogoCyBlanc.png" alt="logo CY"></p>
</footer>

    
<script src="js/iconOnglet.js"></script>



</body>
</html>







