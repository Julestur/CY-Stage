
<?php


if ($_SERVER['SERVER_NAME'] == 'localhost'){
    $host = 'localhost';
    $user = 'jules';
    $pass = 'julesCYTECH2025@!'; //Le mdp pour mon pc
    }
else {

    // A compléter lors de l'hébergement 
    $host = 'localhost';
    $user = 'root';
    $pass = '';

}

try {

    $bdd = new PDO("mysql:host=$host;charset=utf8",$user,$pass);

    $bdd -> exec("CREATE DATABASE IF NOT EXISTS dataStage");
    $bdd -> exec("USE dataStage");

    //Pour tout supprimer pour les tables 
    $bdd->exec("DROP TABLE IF EXISTS candidature");
    $bdd->exec("DROP TABLE IF EXISTS stage");
    $bdd->exec("DROP TABLE IF EXISTS utilisateur");
    $bdd->exec("DROP TABLE IF EXISTS entreprise");
    $bdd->exec("DROP TABLE IF EXISTS statut");
    $bdd->exec("DROP TABLE IF EXISTS classe");





    $bdd -> exec("CREATE TABLE IF NOT EXISTS statut (
                   idStatut INT PRIMARY KEY,
                   libelle VARCHAR(50) NOT NULL)");

    $bdd -> exec("INSERT IGNORE INTO statut (idStatut,libelle) VALUES (1,'admin'),(2,'Etudiant'),(3,'Professeur'),(4,'Entreprise')");

    $bdd -> exec("CREATE TABLE IF NOT EXISTS entreprise (
                   idEntreprise INT AUTO_INCREMENT PRIMARY KEY,
                   nom VARCHAR(50) NOT NULL UNIQUE)");
    $bdd -> exec("INSERT IGNORE INTO entreprise (idEntreprise,nom) VALUES (1,'CYTECH')");

    $bdd -> exec("CREATE TABLE IF NOT EXISTS classe (
                   idClasse INT AUTO_INCREMENT PRIMARY KEY,
                   nom VARCHAR(50) NOT NULL UNIQUE)");
    $bdd -> exec("INSERT IGNORE INTO classe (idClasse,nom) VALUES (1,'Pré ING1'),(2,'Pré ING2'),(3,'ING1 GM'),(4,'ING1 GI'),(5,'ING2 GM'),(6,'ING2 GI'),(7,'ING3 GM'),(8,'ING3S GI')");



    $creation = " CREATE TABLE IF NOT EXISTS utilisateur ( 
                idUtilisateur INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(50) NOT NULL,
                prenom VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                identifiant VARCHAR(50) NOT NULL,
                mdp VARCHAR(255) NOT NULL,
                pdp VARCHAR(100) NOT NULL,
                mdp_tmp VARCHAR(255) NOT NULL,
                idStatut INT NOT NULL,
                idEntreprise INT,
                idClasse INT,
                estVerif INT DEFAULT 0,
                codeVerif VARCHAR(255) DEFAULT NULL,
                FOREIGN KEY (idStatut) REFERENCES statut(idStatut),
                FOREIGN KEY (idEntreprise) REFERENCES entreprise(idEntreprise),
                FOREIGN KEY (idClasse) REFERENCES classe(idClasse)

                )";

    $bdd -> exec($creation);
    $bdd -> exec("INSERT IGNORE INTO utilisateur (idUtilisateur,nom,prenom,email,identifiant,mdp,pdp,mdp_tmp,idStatut,idEntreprise,idClasse,estVerif)
                  VALUES (1,'Turchi','Jules','ju2ju@outlook.fr','jules','jules','profil.png','vide',1,NULL,NULL,1)");
    $bdd -> exec("INSERT IGNORE INTO utilisateur (idUtilisateur,nom,prenom,email,identifiant,mdp,pdp,mdp_tmp,idStatut,idEntreprise,idClasse,estVerif)
                  VALUES (2,'Turchi','Jules','ju2ju2@outlook.fr','julesE','jules','profil.png','vide',2,NULL,3,1)");
    $bdd -> exec("INSERT IGNORE INTO utilisateur (idUtilisateur,nom,prenom,email,identifiant,mdp,pdp,mdp_tmp,idStatut,idEntreprise,idClasse,estVerif)
                  VALUES (3,'Turchi','Jules','ju2ju3@outlook.fr','julesP','jules','profil.png','vide',3,NULL,NULL,1)");





    $bdd -> exec("CREATE TABLE IF NOT EXISTS stage (
                   idStage INT AUTO_INCREMENT PRIMARY KEY,
                   intitule VARCHAR(50) NOT NULL,
                   detail VARCHAR(500) NOT NULL,
                   dateDebut DATE NOT NULL,
                   dateFin DATE NOT NULL,
                   idEntreprise INT,
                   FOREIGN KEY (idEntreprise) REFERENCES entreprise(idEntreprise)

                   )");


    $bdd -> exec("INSERT IGNORE INTO stage (idStage,intitule,detail,dateDebut,dateFin,idEntreprise) 
                  VALUES (1,'Codeur','dev web','2026-10-10','2026-12-12',1)");


    $bdd -> exec("CREATE TABLE IF NOT EXISTS candidature (
                   idCandidature INT AUTO_INCREMENT PRIMARY KEY,
                   statut INT NOT NULL,
                   idStage INT NOT NULL,
                   idEntreprise INT NOT NULL,
                   idUtilisateur INT NOT NULL,
                   FOREIGN KEY (idStage) REFERENCES stage(idStage),
                   FOREIGN KEY (idUtilisateur) REFERENCES utilisateur(idUtilisateur),
                   FOREIGN KEY (idEntreprise) REFERENCES entreprise(idEntreprise)
                )");

    $bdd -> exec("INSERT IGNORE INTO candidature (idCandidature,statut,idStage,idEntreprise,idUtilisateur) 
                  VALUES (1,1,1,1,1)");





} catch (PDOException $e){
    echo "Erreur :" . $e->getMessage();

}

?>

