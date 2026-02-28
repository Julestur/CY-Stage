<!-- <?php
session_start();

if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}
?> -->

<link rel="stylesheet" href="CSS/header.css">



<header>
    <div id="overlay-header"></div>

    <div id="profil">
        <?php echo('<img src="../images_profil/'.$_SESSION['photo-profil'].'" alt="">')?>
        
        <p><?php echo $_SESSION['prenom']?></p>
    </div>
    <div id="overlay"></div>
    <div id="profil-deroulant">
        <p>profil</p>
        <ul>
            <li>
                <a href="../accueil.php">
                    <span>accueil</span>
                    <ion-icon name="school-outline"></ion-icon>
                </a>
            </li>
            <li>
                <a href="../changerMotDePasse.php">
                    <span>changer mot de passe</span>
                    <ion-icon name="shield-half-outline"></ion-icon>
                </a>
            </li>
            <li>
                <a href="../changerPhotoProfil.php">
                    <span>changer photo de profil</span>
                    <ion-icon name="person-circle-outline"></ion-icon>
                </a>
            </li>
            <?php
            
            if($_SESSION['grade'] == 'admin')
            {
                echo '
                <li>
                    <a href="./../ajoutAdmin.php">
                        <span>ajouter profil</span>
                        <ion-icon name="add-circle-outline"></ion-icon>
                    </a>
                </li>
                <li>
                    <a href="./../gestionAdmin.php">
                        <span>supprimer profil</span>
                        <ion-icon name="remove-circle-outline"></ion-icon>
                    </a>
                </li>
                
                ';
            }
            
            ?>
            
            <li>
                <a class="red" href="./deconnexion.php">
                    <span>deconnexion</span>
                    <ion-icon name="exit-outline"></ion-icon>
                </a>
            </li>
        </ul>
    </div>
    <a href="./accueil.php" id="logoCY"><img src="./Images/logo2.png" alt=""></a>
    <h1 class="titre text-header"> GESTION DES STAGES </h1>

    <script src="./menuDeroulant.js"></script>
</header>