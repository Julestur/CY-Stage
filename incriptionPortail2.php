<?php
session_start();


$form = $_SESSION['form_inscription'];
$mail = $form["email"];


if (!isset($_SESSION['codeVerif']) || !isset($_SESSION['form_inscription'])) {
    header("Location: incriptionPortail.php");
    exit;
}

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code_saisi = $_POST['code_saisi'] ?? '';

    if ($code_saisi == $_SESSION['codeVerif']) {
        header("Location: incriptionPortail3.php");
        exit;
    } else {
        $erreur = "Le code saisi est incorrect. Vérifiez vos e-mails.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du compte - CYStage</title>
    <link rel="stylesheet" href="styleconnexion.css">
    <link rel="icon" id="iconOnglet" href="../Images/LogoCyNoir.png" type="image/png">
    
   
</head>
<body>
    <div id="logoCY"><img src="../Images/logo2.png" alt="logo" style="display: block; margin: 20px auto;"></div>
    
    <div class="boite_verif_code">
        <h3 class="titreVerif">Vérification par E-mail</h3>
        <p class="txtVerif">
            Un code de validation à 6 chiffres a été envoyé à l'adresse :<br>
            <strong><?= htmlspecialchars($mail) ?></strong>
        </p>

        <?php if (!empty($erreur)): ?>
            <p style="color: red; font-family: Arial;"><?= $erreur ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="portail">
            <input type="text" name="code_saisi" maxlength="6" placeholder="000000" class="zone_saisie saisie_code" required autofocus>
            
            <div class="bouton2">
                <input type="submit" value="Valider l'inscription" class="bouton_style_verif">
            </div>
        </form>

        <p class="txtVerif" style="margin-top:30px">
            <a href="incriptionPortail.php" style="color: #699ff5;">Retour à l'inscription</a>
        </p>
    </div>
</body>
</html>