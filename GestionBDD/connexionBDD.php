
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

    $bdd = new PDO("mysql:host=$host;dbname=dataStage;charset=utf8",$user,$pass);

} catch (PDOException $e){
    die( "Erreur :" . $e->getMessage());

}

?>

