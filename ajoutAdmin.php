
<!------------------ ATTENTION IL FAUT APPELER CE FICHIER gestionProfil.php ET LE MODIFIER DANS LES AUTRES FICHIER---->


<?php
    session_start();

    require_once 'GestionBDD/connexionBDD.php';


    if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
        header("Location: connexion.php");
        exit;
    }

    // Vous devez etre admin pour acceder a cette page
    if ($_SESSION['grade'] !== 'admin') {
        header("Location: connexion.php");
        exit;
    }


//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != 'vide') 
{
    header("Location: changerMDP_temp.php");
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
        $mail = $_POST['mail'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $mdp = $_POST['mot-de-passe'] ?? '';
        $confirme = $_POST['confirmation-mot-de-passe'] ?? '';
        $nomEntreprise = $_POST['nom_entreprise'] ?? '';

        
    
        // Vérif si le mail ou l'id n'est pas déjà utilisé
        try {
            $bdd->exec("USE dataStage");

            //On recupère les mail et id 
            $requete = "SELECT email,identifiant FROM utilisateur
                        WHERE email = :mail OR identifiant = :pseudo";
            
            $execution = $bdd->prepare($requete);
            $execution->execute([':mail' => $mail,':pseudo' => $pseudo]);

            $res = $execution->fetch(PDO::FETCH_ASSOC);

            // Verif si id et mail non utilisé
            if ($res){
                if($res['email'] === $mail){
                    $erreur_mail = "Cette adresse mail est déjà inscrite";
                }
                else if ($res['identifiant'] === $pseudo){
                    $erreur_mail = "Cet identifiant  est déjà utilisé";
                }
            }

            //Verif la confirmation du mdp
            if ($mdp != $confirme){
                $erreur_mdp = "La confirmation du mot de passe est incorrecte";
            }

            //Redirection vers la page pour ajouter à la BDD
            if(empty($erreur_mail) && empty($erreur_mdp)) {

                $_SESSION['form_inscription'] = $_POST;
                header('Location: enregistrerAdmin.php');
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
            <h2 class="titre1">Inscription sur le portail</h2>
        </div>

        <hr id="ligneHaute">

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
                <input type="text" placeholder="Nom" class="contenu-portail" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                <hr>
                <input type="text" placeholder="Prenom" class="contenu-portail" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>
                <hr>
                <input type="email" placeholder="Adresse e-mail" class="contenu-portail" name="mail" required>
                <hr>
                <input type="text" placeholder="Identifiant" class="contenu-portail" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>" required>
                <hr>
                <input type="password" placeholder="Mot de passe" class="contenu-portail" name="mot-de-passe" required>
                <hr>
                <input type="password" placeholder="Confirmation mot de passe" class="contenu-portail" name="confirmation-mot-de-passe" required>
                <hr>
                <select name="grade" id = type_grade class="selection-administration">
                    <option value="" disabled selected>-- Choisissez un rôle --</option>
                    <option value="admin">Administrateur</option>
                    <option value="Etudiant">Etudiant</option>                  
                    <option value="Professeur">Professeur</option>
                    <option value="Entreprise">Entreprise</option>
                </select>
                <div id="bloc-nom-entreprise" style="display: none;" class="portail">
                    <input type="text" id="nom-entreprise" placeholder="Entrez le nom de l'entreprise" class="contenu-portail" name="nom_entreprise">

                </div>
                
                <div id="bloc_formation" style="display: none;" class="portail">

                    <select name="classe" id = formation class="selection-administration">
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
                <div class="bouton"><input type="submit" value="Inscription" class="boutonAjout"></div>
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


</body>
</html>