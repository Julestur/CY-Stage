<?php



    session_start();

    require_once 'GestionBDD/connexionBDD.php';

    $idSaisi = $_POST["pseudo"] ?? '';
    $mdpSaisi = $_POST["mot-de-passe"] ?? '';

    if (empty($idSaisi) || empty($mdpSaisi)) {
        header("Location: connexion.php");
    exit;
}

    try {


    $commande = "SELECT u.*, s.libelle FROM utilisateur u
                 LEFT JOIN statut s ON u.idStatut = s.idStatut
                 WHERE u.identifiant = :id OR u.email = :id";


    $commandeExec = $bdd -> prepare($commande);
    $commandeExec -> execute([':id' => $idSaisi]);

    $info = $commandeExec->fetch(PDO::FETCH_ASSOC);

    if ($info){


        if ($mdpSaisi == $info['mdp']){


            $_SESSION['connecte'] = TRUE;
            $_SESSION['nom'] = $info['nom'];
            $_SESSION['prenom'] = $info['prenom'];
            $_SESSION['pseudo'] = $info['identifiant'];
            $_SESSION['photo-profil'] = $info['pdp'];
            $_SESSION['mdp_tmp'] = $info['mdp_tmp'];
            $_SESSION['grade'] = $info['libelle'];


            header("Location: accueil.php");
            exit;
        }
        else if ($mdpSaisi == $info['mdp_tmp']){


            $_SESSION['connecte'] = TRUE;
            $_SESSION['nom'] = $info['nom'];
            $_SESSION['prenom'] = $info['prenom'];
            $_SESSION['pseudo'] = $info['identifiant'];
            $_SESSION['photo-profil'] = $info['pdp'];
            $_SESSION['mdp_tmp'] = $info['mdp_tmp'];
            $_SESSION['grade'] = $info['libelle'];


            header("Location: changerMDP_temp.php");
            exit;
        }
        else {
        
            header("Location: connexion.php?error=wrong_login");
            exit;

        }
    }
        }catch (PDOException $e){
            echo "Erreur". $e->getMessage();


        }



    




    



?>