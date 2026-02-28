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
        <?php

        // Ajout dans la BDD
        require_once 'GestionBDD/connexionBDD.php';

        $bdd->exec("USE dataStage");

        if (!isset($_SESSION['form_inscription'])) {
            header("Location: ajoutAdmin.php");
            exit;
        }

        $form = $_SESSION['form_inscription'];
        $nom = $form["nom"];
        $prenom = $form["prenom"];
        $mail = $form["mail"];
        $pseudo = $form["pseudo"];
        $mdp = $form["mot-de-passe"];
        $pdp = "profil.png";
        $mdp_tmp = "vide"; //Variable qui va stocker le mdp temporaire recu par mail
        $statut = $form['grade'];
        $nomEntreprise = $form['nom_entreprise'] ?? NULL; // Recup le nom de l'entreprise
        $classeEtu = $form['classe'] ?? NULL;


        if ($statut == "admin"){ $statutVal = 1;}
        else if ($statut == "Etudiant"){ $statutVal = 2;}
        else if ($statut == "Professeur"){ $statutVal = 3;}
        else if ($statut == "Entreprise"){ $statutVal = 4;}
        else { $statutVal = 2   ;}



        try {

            $idEntreprise = NULL; // Variable qui va servir à stocker l'id de l'entreprise

            if ($statutVal == 4 && !empty($nomEntreprise)){


                $requete = "SELECT idEntreprise FROM entreprise WHERE nom= :nomEntreprise";
                $requeteComplete = $bdd -> prepare($requete);
                $requeteComplete -> execute([':nomEntreprise' => $nomEntreprise]);
                $res = $requeteComplete->fetch(PDO::FETCH_ASSOC); // Organisation du resultat de la requete

                if ($res){ //Si l'entreprise est dans la BDD

                    $idEntreprise = $res['idEntreprise'];

                }
                else { // Si elle n'est pas encore dans la bdd

                    $requete2 = "INSERT INTO entreprise (nom) VALUES (:nomEntreprise)";
                    $requeteComplete2 = $bdd -> prepare($requete2);
                    $requeteComplete2 -> execute([':nomEntreprise' => $nomEntreprise]);
                    $idEntreprise = $bdd->lastInsertId(); // Fct qui donne le dernier id inséré

                }
            }




            $idClasse = NULL; // Variable qui va servir à stocker l'id de la classe

            if ($statutVal == 2 && !empty($classeEtu)){


                $requete = "SELECT idClasse FROM classe WHERE nom= :nomClasse";
                $requeteComplete = $bdd -> prepare($requete);
                $requeteComplete -> execute([':nomClasse' => $classeEtu]);
                $res = $requeteComplete->fetch(PDO::FETCH_ASSOC); // Organisation du resultat de la requete

                if ($res){ //Si l'entreprise est dans la BDD

                    $idClasse = $res['idClasse'];

                }
                else { // Si elle n'est pas encore dans la bdd

                    $requete2 = "INSERT INTO classe (nom) VALUES (:nomClasse)";
                    $requeteComplete2 = $bdd -> prepare($requete2);
                    $requeteComplete2 -> execute([':nomClasse' => $classeEtu]);
                    $idClasse = $bdd->lastInsertId(); // Fct qui donne le dernier id inséré

                }
            }



            $requete = "INSERT INTO utilisateur(nom,prenom,email,identifiant,mdp,pdp,mdp_tmp,idStatut,idEntreprise,idClasse)
                        VALUES (:nom,:prenom,:email,:identifiant,:mdp,:pdp,:mdp_tmp,:idStatut,:idEntreprise,:idClasse)";
            
            $requeteComplete = $bdd -> prepare($requete);

            $requeteComplete -> execute([':nom' => $nom,':prenom' => $prenom, ':email' => $mail, ':identifiant' => $pseudo,
                                        ':mdp' => $mdp,':pdp' => $pdp,':mdp_tmp' => $mdp_tmp,':idStatut' => $statutVal, ':idEntreprise' => $idEntreprise, ':idClasse' => $idClasse ]);



        }
        catch (PDOException $e){
            echo "Erreur". $e->getMessage();


        }


        ?>

        <!-- S'affiche si l'inscription c'est bien déroulée : Permet d'informer l'utilisateur de son inscription -->
        <div class="contenu-centre">
            <h4 class="texte_aff" style="font-size: xx-large;"> Inscription terminée !</h1>
            
            <p class="texte_aff"><?php echo htmlspecialchars($form["prenom"]) . " " ?> peut maintenant <span id="surlignage">se connecter</span>.</p>
            <script src="./js/confetti.js"></script>
            
            <div class = "bouton-position">
            <p><a href="accueil.php"><input type="button" value="Retour" class="bouton-style"></a></p>
            </div>

        </div>
    </div>

</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>