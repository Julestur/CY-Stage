<?php
session_start();

if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}
?>
<?php

// LIRE export.csv DEPUIS LE FORM
$contenu = NULL;

if (isset($_POST["f_excel_text"]) && $_POST["f_excel_text"] != "") {
    $contenu = json_decode($_POST["f_excel_text"], true)[0]['content'];
}

$inscrits = [];

if (!is_null($contenu)) {
    $lines = explode("\n", $contenu);

    // LIRE HEADER
    $header_line = array_shift($lines);
    $header = str_getcsv($header_line, ",", '"', "\\");

    // TROUVER LES INDEX DES COLONNES
    $col_nom = array_search("Nom participant", $header);
    $col_prenom = array_search("Prénom participant", $header);
    $col_type = array_search("Prestations", $header);

    // Lecture des lignes
    foreach ($lines as $line) {
        $elms = str_getcsv($line, ",", '"', "\\");

        if (count($elms) > max($col_nom, $col_prenom, $col_type) && !empty(trim($elms[$col_nom])) && !empty(trim($elms[$col_prenom]))) {
            $type_activite = strtolower(trim($elms[$col_type]));

            $type = "encadrant";
            if (strpos($type_activite, "journée ski") !== false) {
                $type = "ski";
            } elseif (strpos($type_activite, "journée snow") !== false) {
                $type = "snow";
            }

            $inscrits[] = [
                'nom' => $elms[$col_nom],
                'prenom' => $elms[$col_prenom],
                'type' => $type
            ];
        }
    }
}


// LIRE skieur.csv
$niveaux_ski = [];
$licenses_ski = [];

if (($handle = fopen("saison/skieur.csv", "r")) !== false) {
    $header = fgetcsv($handle, 1000, ",", '"', "\\");

    // TROUVER LES INDEX DES COLONNES
    $col_nom = array_search("Nom", $header);
    $col_prenom = array_search("Prénom", $header);
    $col_niveau = array_search("Niveau pour cette saison", $header);
    $col_license = array_search("N° License", $header);

    while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {

        $cle = strtolower(trim($data[$col_nom])) . "_" . strtolower(trim($data[$col_prenom]));
        $niveaux_ski[$cle] = $data[$col_niveau];
        $licenses_ski[$cle] = $data[$col_license];

    }
    fclose($handle);
}

// LIRE snowboardeur.csv
$niveaux_snow = [];
$licenses_snow = [];

if (($handle = fopen("saison/snowboardeur.csv", "r")) !== false) {
    $header = fgetcsv($handle, 1000, ",", '"', "\\");

    // TROUVER LES INDEX DES COLONNES
    $col_nom = array_search("Nom", $header);
    $col_prenom = array_search("Prénom", $header);
    $col_niveau = array_search("Niveau pour cette saison", $header);
    $col_license = array_search("N° License", $header);

    while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {

        $cle = strtolower(trim($data[$col_nom])) . "_" . strtolower(trim($data[$col_prenom]));
        $niveaux_snow[$cle] = $data[$col_niveau];
        $licenses_snow[$cle] = $data[$col_license];

    }
    fclose($handle);
}

// LIRE encadrants.csv
$encadrants_ski = [];
$encadrants_snow = [];
$encadrants_license = [];

if (($handle = fopen("saison/encadrants.csv", "r")) !== false) {
    $header = fgetcsv($handle, 1000, ",", '"', "\\");

    $col_discipline = array_search("Discipline", $header);
    $col_nom = array_search("NOM", $header);
    $col_prenom = array_search("Prénom", $header);
    $col_license = array_search("N° License", $header);

    while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
        $type = strtolower(trim($data[$col_discipline]));
        $encadrant = strtolower(trim($data[$col_nom])) . "_" . strtolower(trim($data[$col_prenom]));

        // echo("<pre>".$type."</pre>");
        $encadrants_license[$encadrant] = $data[$col_license];

        if ($type == "ski") {

            $encadrants_ski[$encadrant] = $encadrant;

        } elseif ($type == "snowboard") {

            $encadrants_snow[$encadrant] = $encadrant;

        }
    }
    fclose($handle);
}

// GROUPE
function definirGroupe($niveau, $type) {

    if($type == "ski")
    {
        if($niveau == "DEBUTANT")
        {
            return "Groupe 1";
        }
        else if($niveau == "1*")
        {
            return "Groupe 2";
        }
        else if($niveau == "2*")
        {
            return "Groupe 3";
        }
        else if($niveau == "3*")
        {
            return "Groupe 4";
        }
        else if($niveau == "FLECHE")
        {
            return "Groupe 5";
        }
    }
    else
    {
        return "Groupe 6";
    }
}

function groupeEncadrant($encadrants_ski, $encadrants_snow, $inscrits)
{
    $groupe_encadrants = [];

    // Construire table des inscrits
    $cles_inscrits = [];
    foreach ($inscrits as $personne) {
        $cle_inscrit = strtolower(trim($personne['nom'])) . "_" . strtolower(trim($personne['prenom']));
        $cles_inscrits[$cle_inscrit] = true;
    }

    // Répartition équilibrée des encadrants ski dans les groupes 1 à 5
    $cles_ski = array_keys($encadrants_ski);
    shuffle($cles_ski);

    $nb_groupes_ski = 5;
    $compteur = 0;

    foreach ($cles_ski as $cle) {
        if (isset($cles_inscrits[$cle])) {
            // Groupe de 1 à 5 en boucle équilibrée
            $groupe_num = ($compteur % $nb_groupes_ski) + 1;
            $groupe_encadrants[$cle] = "Groupe " . $groupe_num;

            $compteur++;
        }
    }

    // Tous les encadrants snow vont dans le groupe 6
    $cles_snow = array_keys($encadrants_snow);

    foreach ($cles_snow as $cle) {
        if (isset($cles_inscrits[$cle])) {
            $groupe_encadrants[$cle] = "Groupe 6";
        }
    }

    return $groupe_encadrants;
}


$groupe_encadrants = groupeEncadrant($encadrants_ski, $encadrants_snow, $inscrits);

// GENERATION DU FICHIER FINAL
// Récupérer la date du formulaire
$date_sortie = isset($_POST["date-sortie"]) ? $_POST["date-sortie"] : date('Y-m-d');

// Transformer la date en timestamp
$timestamp = strtotime($date_sortie);

// Table mois en français
$mois_fr = [
    1 => "janvier", "février", "mars", "avril", "mai", "juin",
    "juillet", "août", "septembre", "octobre", "novembre", "décembre"
];

// Construire le nom du fichier
$jour = date('j', $timestamp); // sans le 0 devant
$mois = $mois_fr[(int)date('n', $timestamp)]; // 'n' pour 1-12
$annee = date('Y', $timestamp);

$fichier_final = "sorties/" . $jour . " " . $mois . " " . $annee . ".csv";

$handle = fopen($fichier_final, 'w');

fputcsv($handle, ["N° Licence", "Nom", "Prénom", "Type", "Niveau", "Groupe"], ";", '"', "\\");

foreach ($inscrits as $personne) {
    $cle = strtolower(trim($personne['nom'])) . "_" . strtolower(trim($personne['prenom']));

    if ($personne['type'] == "ski") {
        $niveau = $niveaux_ski[$cle] ?? "Inconnu";
        $groupe = definirGroupe($niveau, $personne['type']);

        $personne['journee'] = "Ski";
        $personne['lisence'] = $licenses_ski[$cle];
    } elseif ($personne['type'] == "snow") {
        $niveau = $niveaux_snow[$cle] ?? "Inconnu";
        $groupe = definirGroupe($niveau, $personne['type']);

        $personne['journee'] = "Snow";
        $personne['lisence'] = $licenses_snow[$cle];
    } else if ($personne['type'] == "encadrant") {
        $niveau = "ENCADRANT";
        $groupe = $groupe_encadrants[$cle] ?? "Groupe ?";

        if (isset($encadrants_ski[$cle])) {
            $personne['journee'] = "Ski";
        } else {
            $personne['journee'] = "Snow";
        }

        $personne['lisence'] = $encadrants_license[$cle];
    }

    fputcsv($handle, [
        $personne['lisence'],
        $personne['nom'],
        $personne['prenom'],
        ucfirst($personne['journee']),
        $niveau,
        $groupe,
    ], ";", '"', "\\");
}

fclose($handle);

// Redirection vers accueil avec message
header("Location: accueil.php");
exit;
?>
