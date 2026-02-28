<!-- SUPPRIME UNE SORTIE -->

<?php
session_start();

if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}
?>

<?php
if (isset($_GET['sortie'])) {
    $nom = basename($_GET['sortie']);
    $chemin = './sorties/' . $nom;

    unlink($chemin);
} 

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>