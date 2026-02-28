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



// Trier fichiers par ordre decroissant de date
$repertoire = "sorties/";
$mois = [
    "janvier" => "January", "février" => "February", "mars" => "March",
    "avril" => "April", "mai" => "May", "juin" => "June",
    "juillet" => "July", "août" => "August", "septembre" => "September",
    "octobre" => "October", "novembre" => "November", "décembre" => "December"
];

$fichiers = [];

foreach (glob($repertoire . "*.csv") as $filepath) 
{
    $file = basename($filepath);
    $nom = pathinfo($file, PATHINFO_FILENAME);
    $nom_en = str_ireplace(array_keys($mois), array_values($mois), $nom);
    $timestamp = strtotime($nom_en);
    if ($timestamp) 
    {
        $fichiers[$file] = $timestamp;
    }
}

arsort($fichiers); // Tri décroissant

// recuperer info sur la derniere sortie (fichier le plus recent)
$dernierFichier = array_key_first($fichiers);
$chemin = $repertoire . $dernierFichier;

$lignes = file($chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$nb_total = count($lignes) - 1;
$nb_encadrants = 0;

if (($handle = fopen($chemin, "r")) !== false) 
{
    $headers = fgetcsv($handle, 0, ";",'"',"\\");

    while (($data = fgetcsv($handle, 0, ";",'"',"\\")) !== false) {
        
        $niveau_index = array_search("Niveau", $headers);
        if (strtolower(trim($data[$niveau_index])) == "encadrant") {
            $nb_encadrants++;
        }
    }
    fclose($handle);
}

$nb_jeunes = $nb_total - $nb_encadrants;
$heure = date("H");
?>








<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="accueilStyle.css">


            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Découvrez l'espace de gestion du l'association ALB avec son système de tri automatique des tableaux excels à l'aide d'un simple clic.">
            <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
            <link rel="stylesheet" href="styleDropZone.css">
            
            
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <title>Accueil</title>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <?php include("webappLinks.php"); ?>

    </head>
    <body>

    <!-- Header------------------------------------------------------------------------------------------------------ -->
    <?php include('header.php'); ?>

    <!-- Content----------------------------------------------------------------------------------------------------- -->
    <div class="page-dimension">
        <div class="contenu-principal">
            <div class="accueil"> 
                <?php if($heure >= 6 && $heure < 18): ?>
                <h2 class="titre1"> Bonjour, <?php echo $_SESSION['prenom']?> !</h2>
                <?php else: ?>
                <h2 class="titre1"> Bonsoir, <?php echo $_SESSION['prenom']?> !</h2>
                <?php endif; ?>
            </div>

            

            
            
            
            
            
            
            
<!-- Contenue de la page ####################################################################################### -->
     
            
<?php

    // AFFICHAGE DIFFERENT SELON LE PROFILS

    // ###### ADMIN #####
    
    if ($_SESSION['grade'] == 'admin') {   

        
        ?><link rel="stylesheet" href="./CSS/accueil/accueilAdmin.css"><?php

        require_once 'GestionBDD/connexionBDD.php';
        $bdd->exec("USE dataStage");

        echo "<h2 class='titre2'> Tableau de bord</h2>";

        try{ 
        //Nb étudiants
        $commande = $bdd->query("SELECT COUNT(*) FROM utilisateur WHERE idStatut = 2");
        $nbEtudiant = ($commande) ? $commande->fetchColumn() : 0; //Met à 0 si table vide

        //Nb entreprises
        $commande2 = $bdd->query("SELECT COUNT(*) FROM entreprise");
        $nbEntreprise = ($commande2) ? $commande2->fetchColumn() : 0;

        //Nb prof
        $commande3 = $bdd->query("SELECT COUNT(*) FROM utilisateur WHERE idStatut = 3");
        $nbProf = ($commande3) ? $commande3->fetchColumn() : 0;


        //Nb stages
        $commande4 = $bdd->query("SELECT COUNT(*) FROM stage");
        $nbStage = ($commande4) ? $commande4->fetchColumn() : 0;


        }  catch (PDOException $e){
    
            echo "Erreur :" . $e->getMessage();
            $nbEtudiant = $nbEntreprise = $nbProf = $nbStage = 0;

        }
        
        ?>

    <!-- AFFICHAGE DU TABLEAU DE BORD -->
        
      <div class="tab_bord">
            <div class="carre_style">
                <div class="contenu_carte">
                    <h3>Étudiants</h3>
                    <p class="valeur"><?php echo $nbEtudiant; ?></p>                        
                </div>
                <ion-icon name="school-outline" class="style_icone"></ion-icon>
            </div>

            <div class="carre_style">
                <div class="contenu_carte">
                    <h3>Entreprises</h3>
                    <p class="valeur"><?php echo $nbEntreprise; ?></p>
                </div>
                <ion-icon name="business-outline" class="style_icone"></ion-icon>
            </div>

            <div class="carre_style">
                <div class="contenu_carte">
                    <h3>Stages</h3>
                    <p class="valeur"><?php echo $nbStage; ?></p>
                </div>
                <ion-icon name="briefcase-outline" class="style_icone"></ion-icon>
            </div>

            <div class="carre_style">
                <div class="contenu_carte">
                    <h3>Professeurs</h3>
                    <p class="valeur"><?php echo $nbProf; ?></p>
                </div>
                <ion-icon name="people-outline" class="style_icone"></ion-icon>
            </div>
    </div>


<!-- AFFICHAGE DE LA BARRE DE CHOIX -->

<?php 

$elt_aff = isset($_GET['choix']) ? $_GET['choix'] : 'candidatures'; // On choisit quelle catégorie va etre affichée par défaut (stage)

//Création de la barre de choix ?>
<div class="barre_choix">

    <a href = "?choix=candidatures" class = "bouton_choix <?php echo ($elt_aff == 'candidatures') ? 'actif' : ''; ?>" > <!-- Permet d'ajouter uen annimation en ajoutant la classe actif-->
    <ion-icon name="paper-plane-outline"></ion-icon> Candidatures </a>

    <a href = "?choix=stage" class = "bouton_choix <?php echo ($elt_aff == 'stage') ? 'actif' : ''; ?>" > <!-- Permet d'ajouter uen annimation en ajoutant la classe actif-->
    <ion-icon name="briefcase-outline"></ion-icon> Stages </a>

    <a href = "?choix=etudiant" class = "bouton_choix <?php echo ($elt_aff == 'etudiant') ? 'actif' : ''; ?>" > <!-- Permet d'ajouter uen annimation en ajoutant la classe actif-->
    <ion-icon name="school-outline"></ion-icon> Etudiants </a>

    <a href = "?choix=prof" class = "bouton_choix <?php echo ($elt_aff == 'prof') ? 'actif' : ''; ?>" > <!-- Permet d'ajouter uen annimation en ajoutant la classe actif-->
    <ion-icon name="people-outline"></ion-icon> Professeurs </a>

    <a href = "?choix=entreprise" class = "bouton_choix <?php echo ($elt_aff == 'entreprise') ? 'actif' : ''; ?>" > <!-- Permet d'ajouter uen annimation en ajoutant la classe actif-->
    <ion-icon name="business-outline"></ion-icon> Entreprises </a>

</div>

      
<!-- AFFICHAGE DE LA LISTE DES INFORMATIONS -->

    <?php


    $elt_aff = isset($_GET['choix']) ? $_GET['choix'] : 'candidatures'; // On choisit quelle catégorie va etre affichée par défaut (stage)

    
    switch($elt_aff){

            case 'prof' :
                $titre = "Liste des professeurs";
                $requete = "SELECT nom,prenom,email FROM utilisateur WHERE idStatut = 3";
                break;


            case 'etudiant' :
                
                $titre = "Liste des étudiants ";
                $requete = "SELECT u.nom, u.prenom,u.email,c.nom as nomClasse FROM utilisateur u
                            LEFT JOIN classe c ON u.idClasse = c.idClasse
                            WHERE idStatut = 2";
                break;

            case 'entreprise' :
                
                $titre = "Liste des entreprises ";
                $requete = "SELECT nom FROM entreprise";
                break;

            case 'stage' :
                
                $titre = "Liste des offres de stage ";
                $requete = "SELECT s.intitule,s.detail,s.dateDebut,s.dateFin,e.nom AS nomEntreprise FROM stage s
                            INNER JOIN entreprise e ON s.idEntreprise = e.idEntreprise";
                break;



            default: // Par défaut les candidatures 

                $titre = "Liste des candidatures ";
                $requete = "SELECT s.intitule AS S_Description ,e.nom AS Nom_Entreprise, u.prenom AS prenomEtu, u.nom AS nomEtu, c.statut AS numStatut, s.dateDebut AS debut,s.dateFin AS fin, s.detail AS stageDetail 
                             FROM candidature c 
                             INNER JOIN stage s ON c.idStage = s.idStage
                             INNER JOIN entreprise e ON c.idEntreprise = e.idEntreprise
                             INNER JOIN utilisateur u ON c.idUtilisateur = u.idUtilisateur";
                break;
            
    }

    try {
        $requeteComplete = $bdd -> prepare($requete);
        $requeteComplete -> execute();
        $info = $requeteComplete->fetchAll(PDO::FETCH_ASSOC);
    } 
     catch (PDOException $e){
            echo "Erreur". $e->getMessage();


        }
 

?>

    <div id="barreLatérale">
        <div id="contenuBarreLat">

                <span onclick = "fermerBarreLat()"> &times; </span>
                <h2 id="titre"></h2> <br><br><br><br>
                <p id="date"></p><br><br>
                <p id="description"></p><br><br>
                <p id="statut"></p>




        </div>
    </div>








    <div class = tab_info>
        
        <?php

        echo "<div class=zoneTitre>";
                echo "<h2 class=titreInfo>$titre<h2>";

                if ($elt_aff === "stage"){
                    echo "<button onclick= 'window.location.href = \"ajoutStage.php\";' class=boutonAjout>+ Ajouter</button>";

                }
                else if ($elt_aff === "etudiant" || $elt_aff === "prof" ){
                    echo "<button onclick= 'window.location.href = \"ajoutAdmin.php\";' class=boutonAjout>+ Ajouter</button>";

                }
                else if ($elt_aff === "entreprise"){
                    echo "<button onclick= 'window.location.href = \"ajoutEntreprise.php\";' class=boutonAjout>+ Ajouter</button>";

                }

        echo "</div>";
        
        

        // Affichage des en-tete du tableau
        echo "<div class = contenu>";

        echo "<table class = tabInfo>";

            echo "<thead>";
            echo "<tr>";
            switch($elt_aff){


            case 'prof' :
                echo "<th> Nom </th>";
                echo "<th> Prenom </th>";
                echo "<th> Email </th>";
                echo "<th> Action </th>"; 
                break;


            case 'etudiant' :
                
                echo "<th> Nom </th>";
                echo "<th> Prenom </th>";
                echo "<th> Email </th>";
                echo "<th> Classe </th>";
                echo "<th> Action </th>";          
                break;

            case 'entreprise' :
                
                echo "<th> Nom </th>";
                echo "<th> Action </th>";
                break;


            case 'stage' :
                
                echo "<th> Titre </th>";
                echo "<th> Entreprise </th>";
                echo "<th> Début </th>";
                echo "<th> Fin </th>";
                echo "<th> Action </th>";
                break;


            default: // Par défaut les stages 

                echo "<th> Titre </th>";
                echo "<th> Entreprise </th>";
                echo "<th> Nom </th>";
                echo "<th> Prénom </th>";
                echo "<th> Statut </th>";
                echo "<th> Début </th>";
                echo "<th> Fin </th>";
                echo "<th> Action </th>";
                break;
            
    }
            echo "</tr>";
            echo "</thead>";




    // Affichage des elements 
    foreach ($info as $ligne) {


            echo "<tr>";
            switch($elt_aff){


            case 'prof' :
                echo "<td> ".$ligne['nom']." </td>";
                echo "<td> ".$ligne['prenom']." </td>";
                echo "<td> ".$ligne['email']." </td>";
                echo "<td> <button> Détails </button> </td>"; 
                break;


            case 'etudiant' :
                
                echo "<td> ".$ligne['nom']." </td>";
                echo "<td> ".$ligne['prenom']." </td>";
                echo "<td> ".$ligne['email']." </td>";
                echo "<td> ".$ligne['nomClasse']." </td>";
                echo "<td> <button> Détails </button> </td>";          
                break;

            case 'entreprise' :
                
                echo "<td> ".$ligne['nom']." </td>";
                echo "<td> <button> Détails </button> </td>";
                break;



            case 'stage' :
                
                echo "<td> ".$ligne['intitule']." </td>";
                echo "<td>".$ligne['nomEntreprise']."</td>";
                echo "<td> ".$ligne['dateDebut']." </td>";
                echo "<td> ".$ligne['dateFin']." </td>";
                echo "<td> <button> Détails </button> </td>";
                break;



            default: // Par défaut les candidatures 

                echo "<td>".$ligne['S_Description']."</td>";
                echo "<td>".$ligne['Nom_Entreprise']."</td>";
                echo "<td>".$ligne['prenomEtu']." </td>";
                echo "<td> ".$ligne['nomEtu']." </td>";
                
                if ($ligne['numStatut'] == 1){
                    echo "<td> <ion-icon name='checkmark-circle-outline'></ion-icon></td>";
                }
                else if ($ligne['numStatut'] == 2){
                    echo "<td> <ion-icon name='send-outline'></ion-icon></td>";
                }
                else if ($ligne['numStatut'] == 3){
                    echo "<td> <ion-icon name='close-circle-outline'></ion-icon></td>";
                }
                echo "<td> ".$ligne['debut']." </td>";
                echo "<td> ".$ligne['fin']." </td>";
                echo "<td> <button info_titre=\"" . $ligne['S_Description'] . "\" info_description=\"" . $ligne['stageDetail'] . "\" info_debut=\"" . $ligne['debut'] . "\" info_fin=\"" . $ligne['fin'] . "\" info_statut=\"" . $ligne['numStatut'] . "\" onclick='ouvrirBarreLat(this)' class='boutonDetails'> Détails </button> </td>";
                break;
            
        }
        echo "</tr>";

    }


        echo "</table>";
        echo "</div>";


                ?>


    </div>

    <script> 
    // JS pour gérer l'apparition de la barre latérale 

        function ouvrirBarreLat(bouton){
            
                const titre = bouton.getAttribute('info_titre');
                const description = bouton.getAttribute('info_description');
                const debut = bouton.getAttribute('info_debut');
                const fin = bouton.getAttribute('info_fin');
                const statut = bouton.getAttribute('info_statut');



                document.querySelector("#titre").innerText = titre;
                document.querySelector("#date").innerText = "Date : du "+debut+" au "+fin;
                document.querySelector("#description").innerText = "Description du poste : "+description;
                
                if (statut == 1){

                    document.querySelector("#statut").innerText = "Statut : Candidature validée";

                }
                else if (statut == 2){

                    document.querySelector("#statut").innerText = "Statut : En cours de validation";

                }
                else if (statut == 3){

                    document.querySelector("#statut").innerText = "Statut : Refusée";

                }
                document.getElementById("barreLatérale").style.width = "500px";
        }


        function fermerBarreLat(){
            document.getElementById("barreLatérale").style.width = "0";
        }

    </script>




















<?php
       
        exit;
    }
     if ($_SESSION['grade'] == 'Etudiant') {   
        echo "<h2 class='titre2'> Depot d'un nouveau fichier </h2>";
        exit;
    }
     if ($_SESSION['grade'] == 'Professeur') {   
        echo "<h2 class='titre2'> Depot d'un nouveau fichier </h2>";
        exit;
    }
     if ($_SESSION['grade'] == 'Entreprise') {   
        echo "<h2 class='titre2'> Depot d'un nouveau fichier </h2>";
        exit;
    }
    

?>




            <div class="contenu-accueil">
                

                <div class="depot">
                    <div class="aff_un_post">
                        
                        <h2 class="titre2"> Depot d'un nouveau fichier </h2>
                        
                    </div>

                    <!-- Affichage et gestion du drag-n-drop ------------------------------------------------------------------------------------------------------ -->

                    <div class="drag-n-drop"> 
                        <form action="./repartition.php" method="post" enctype="multipart/form-data">
                            <section id="drag-n-drop">
                                <input type="hidden" name="f_excel_text" id="input-hidden">
                                <input type="file" name="f_excel" id="input-file" accept=".csv" style="display: none;">
                                
                                <div id="drop-zone">
                                    <ion-icon name="download-outline" id="icon-file-drop"></ion-icon>
                                    <pre id="info-drop-zone">Veuillez déposer un fichier .csv</pre>
                                </div>
                        
                            </section>

                            <p class="info">Prenez le temps de vérifier que le fichier déposé soit celui de assoConnect.com</p>

                            <div class="boutons-drag-n-drop">
                                <div class="contenu-date-sortie">
                                    <!-- <span for="date-sortie">Date de la sortie</span> -->
                                    <input type="date" name="date-sortie" id="" required>
                                </div>
                                

                                <div class = "bouton-position-envoi">
                                    <button class="bouton-style-envoi" id="bouton-envoi" type="submit" disabled>
                                        <p>Envoyer</p>
                                        <ion-icon name="paper-plane-outline"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <script src="./dragAndDrop.js"></script>

                </div>

                <div class="historique">
                    

                    <div class="info-derniere-sortie">
                        <h2> Dernière sortie : <span class="derniere-sortie"><?php echo(pathinfo(array_key_first($fichiers), PATHINFO_FILENAME)); ?></span></h2>
                        <?php
                            echo "<p>Nombre de participants : ".$nb_total." </p>";
                            echo "<p>Nombre d'encadrants : ".$nb_encadrants." </p>";
                            echo "<p>Nombre de jeunes : ".$nb_jeunes." </p>";
                        ?>
                    </div>

                    <h2 class="titre2"> Sorties précédentes </h2>
                    <?php
                    
                    if(sizeof($fichiers) == 1)
                    {
                        echo '<pre class="info">Créez une seconde sortie</pre>';
                    }
                    
                    ?>
                    <div class="scroll-historique">
                        <?php

                            echo "<ul>";
                            foreach ($fichiers as $file => $time) {
                                $nom = pathinfo($file, PATHINFO_FILENAME);
                                echo "<li class='tag-sortie no-select'>
                                        <ion-icon class='tag-sortie-suppr' name='remove-outline'></ion-icon>
                                        <div data-lien='" . $file . "' target='_blank'>" . $nom . "</div>
                                    </li>";
                            }
                            echo "</ul>";
                        ?>
                    </div>
                </div>
            </div>
        </div>                  
            



<!-- ####################################################################################### -->




        <!-- Footer------------------------------------------------------------------------------------------------------ -->
        <?php include('footer.php');?>
    </div>      

    <script src="./app.js"></script>
    <script src="js/iconOnglet.js"></script>

    
    </body>
</html>
