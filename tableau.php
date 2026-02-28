<link rel="stylesheet" href="tableauStyle.css">
<?php

// Récupérer le nom de fichier depuis l'URL
$fichier_nom = isset($_GET['sortie']) ? $_GET['sortie'] : '';
$chemin_fichier = "sorties/" . $fichier_nom;

$nom = pathinfo($fichier_nom, PATHINFO_FILENAME);

$lignes     = [];
$headers    = [];

if (($fichier_ouvert = fopen($chemin_fichier, "r")) !== false) {
    $premiere_ligne = true;
    while (($donnees = fgetcsv($fichier_ouvert, 1000, ";", '"', "\\")) !== false) {
        if ($premiere_ligne) {
            $headers = $donnees;
            $premiere_ligne = false;
        } else {
            $lignes[] = $donnees;
        }
    }
    fclose($fichier_ouvert);
}

// On trie les lignes par "Groupe"
$groupes = [];

foreach ($lignes as $ligne) {
    // Supposons que la colonne "Groupe" est la dernière
    $index_colonne_groupe = count($headers) - 1;
    $nom_groupe = $ligne[$index_colonne_groupe];

    if (!isset($groupes[$nom_groupe])) {
        $groupes[$nom_groupe] = [];
    }

    $groupes[$nom_groupe][] = $ligne;
}


// AFFICHAGE
echo '<p class="logo"><img src="../Images/logoSki.png" alt="logo sortie samedi neige"></p>';
echo "<h1>SAMEDI NEIGE</h1>";
echo "<h2><u>Date de sortie :</u> " . $nom . "</h2>";

foreach ($groupes as $nom_groupe => $eleves_du_groupe) {

    echo "<h3>$nom_groupe</h3>";
    
    echo "<table>";
    echo "<thead><tr>";

    foreach ($headers as $colonne) {
        echo "<th>" . $colonne . "</th>";
    }
    echo "<th>Présent</th>"; // colonne supplémentaire
    echo "</tr></thead><tbody>";

    foreach ($eleves_du_groupe as $ligne) {
        $estEncadrant = FALSE;
        foreach ($ligne as $valeur) {
            if (($valeur == "encadrant") || ($valeur == "ENCADRANT")) {
                $estEncadrant = true;
            }
        }
        if ($estEncadrant) {
            echo '<tr class="encadrant">';
        } else {
            echo '<tr>';
        }

        foreach ($ligne as $valeur) {
            echo "<td>" . $valeur . "</td>";
        }

        echo '<td style="text-align: center;"></td>'; // case Présent vide
        echo "</tr>";
    }

    echo "</tbody></table>";
}

echo "<p id='avertissement'>Pour <b>afficher les couleurs de cette page</b> en imprimant le fichier, cliquez sur <b>'Imprimer la page'</b> puis aller dans <b>Plus d'option > <u>Cochez</u> Arrière-plan</b>.</p>";
echo '<button onclick="window.print()" class="imprimer">Imprimer la page</button>';
echo '<a href="'.$chemin_fichier.'" class="telechargement">Télécharger en CSV</a>';
echo '<div class="retour"><a href="accueil.php"> Retour <<< </a></div>'

?>
