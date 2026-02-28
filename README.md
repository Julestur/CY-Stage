# **Projet ALB sortie ski Equipe 1**
> Projet d'informatique de deuxième année <br>
> **Réalisé par** BARTOLI Antoine, MALGOUYRES Mathys, PRETO Tilio et TURCHI Jules


<div align="center">
  <img src = "Images/logo.png/" alt="logo ALB"/>
</div>


## **Mise en contexte**

Dans le cadre du projet de fin d'année, nous avons eu l'opportunité de réaliser une application pour permettre à l'association ALB située à Billère de faciliter la gestion des soties ski. Nous avons donc réaliser un site internet permettant aux organisateur de trier et organiser des fichiers exel afin de simplifier leur lecture et la gestion.


## **Déroulement du projet**

Ce projet s'est déroulé en plusieurs étapes : <br>
- Première prise de contact<br>
- Conception du [cahier des charges](https://ucergyfr.sharepoint.com/sites/ProjetIC22/Documents%20partages/General/Réunion%20initiale%20+%20cahier%20des%20charges/BARTOLI,%20MALGOUYRES,%20PRETO,%20TURCHI%20-%20Cahier%20des%20charges.pdf?CT=1749595510820&OR=ItemsView&wdOrigin=TEAMSFILE.FILEBROWSER.DOCUMENTLIBRARY)<br>
- Validation du cahier des charges<br>
- Développement du projet<br>
- Rendu final : code + rapport<br>

## **Démarrage**


### **Prérequis**

#### **Sur Linux/MacOS**

L'execution sur Linux et MacOS ne demande aucune manipulation préalable.

#### **Sur Windows**

Il est necessaire d'installer XAMPP. Pour cela, il faut suivre le [tuto](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/4237816-preparer-son-environnement-de-travail).

### **Lancement**

Pour lancer l'application web, il suffit de lancer le fichier "connexion.php" en utilisant localhost.
Pour voir vos identifiant/mail et mot de passe utilisés sur le site, regardez <b>admin.csv</b>. 


##  **Fonctionalités**

### 1- Connexion

En lancant le fichier "connexion.php", on accède à un portail de connexion permettant de se connecter grace à un identifiant ou mail et un mot de passe. Si le mot de passe est oublié par la personne il suffit de cliquer sur "mot de passe oublié" pour accéder à une page de réinitialisation du mot de passe. En effet, un nouveau temporaire sera envoyé par mail à l'adresse correspondant au compte.


### 2- Accueil et fonctionnement

Apres connexion, l'utilisateur arrive sur une page d'acceuil. S'il clique sur sa photo de profil le menu des options s'affiche. En bas de la page un "drag and drop" est disponble afin de déposer le fichier que l'on veux traiter, il faut ensuite sélectionner la date et envoyer puis la sortie s'ajoute automatiquement sur la partie droite de la page. De plus, un historique des sorties est disponible.


### 3- Fonctionalités secondaire

Les fonctionalités ne sont pas les memes pour les administrateurs et les sous-administrateurs.

#### Pour les administrateurs

L'administrateur a tout les droits il peut :
- Changer son mot de passe
- Changer sa photo de profil
- Ajouter des profils
- Supprimer des profils
- Traiter des sorties

#### Pour les sous-administrateurs

Le sous-administrateur quant à lui peut uniquement :
- Changer son mot de passe
- Changer sa photo de profil
- Traiter des sorties

### Vous n'avez plus qu'à chausser vos ski et profiter !



@2024, CYtech
