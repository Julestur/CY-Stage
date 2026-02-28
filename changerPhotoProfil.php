<?php
session_start();

if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) {
    header("Location: connexion.php");
    exit;
}

//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != 'vide') 
{
    header("Location: changerMDP_temp.php");
    exit;
}

if (isset($_FILES['photo-profil'])) {
    $photo_nom = $_FILES['photo-profil']['name'];
    $photo_tmp = $_FILES['photo-profil']['tmp_name'];

    $extension = pathinfo($photo_nom, PATHINFO_EXTENSION);
    $pseudo_session = $_SESSION['pseudo'];
    $photo_fichier = $pseudo_session . "_" . time() . "." . $extension;

    // On supprime l'ancienne photo s'il y en a une
    if (isset($_SESSION['photo-profil']) && $_SESSION['photo-profil'] != 'profil.png') {
        $ancienne_photo = "images_profil/" . $_SESSION['photo-profil'];
        if (file_exists($ancienne_photo)) {
            unlink($ancienne_photo);
        }
    }

    move_uploaded_file($photo_tmp, "images_profil/" . $photo_fichier);

    $_SESSION['photo-profil'] = $photo_fichier;

    
    
    

    //Ajout dans la BDD 



    
    
    
    $fichier = 'admin.csv';
    $lignes = [];

    if (($handle = fopen($fichier, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
            if ($data[3] == $pseudo_session) {
                $data[5] = $photo_fichier; // colonne photo
            }
            $lignes[] = $data;
        }
        fclose($handle);
    }

    if (($handle = fopen($fichier, 'w')) !== false) {
        foreach ($lignes as $ligne) {
            fputcsv($handle, $ligne, ";", '"', "\\");
        }
        fclose($handle);
    }

    header("Location: accueil.php");
}
?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Montrez votre plus beau sourire avec la personnalisation de votre photo de profil. Vous pouvez le faire à volonté !">
    <title>Changer Photo De Profil</title>

    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    <link rel="stylesheet" href="accueilStyle.css">
    <link rel="stylesheet" href="styleDropZone.css">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <?php include("webappLinks.php"); ?>
</head>




<body>
<!-- Header------------------------------------------------------------------------------------------------------ -->
<?php require('header.php'); ?>

<!-- Content----------------------------------------------------------------------------------------------------- -->
<div class="page-dimension"> 
    <div class="contenu-principal">  
        <div class="accueil">  
                <h2 class="titre1">Changement de photo de profil</h2>
            </div>

            <hr id="redline-mdp">
            <form action="./changerPhotoProfil.php" method="post" enctype="multipart/form-data" class="portail">
                <div id="drop-zone-photo">
                    <ion-icon name="image-outline" id="icon-changement-photo-profil"></ion-icon>
                    <input type="file" name="photo-profil" id="input-changement-photo-profil" required>
                </div>
                <p class="info">Veuillez deposer une photo au format .png ou .jpg</p>
                <div class="bouton"><input type="submit" value="Enregistrer" id="bouton-style-connexion"></div>
            </form>

            <script src="./changerPhotoProfil.js"></script>
    </div>


    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>
    <script src="js/iconOnglet.js"></script>

</body>
</html>