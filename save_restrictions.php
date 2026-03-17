<?php
require_once("db_natbox.php");

$jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];

$appareilGlobal = $pdo->query("SELECT id FROM appareils WHERE nom = 'TOUS_LES_APPAREILS' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if(!$appareilGlobal){
    header("Location: gestion_restrictions.php");
    exit;
}

$appareil_global_id = $appareilGlobal['id'];

$stmtDelete = $pdo->prepare("DELETE FROM regles_parentales WHERE appareil_id = ?");
$stmtDelete->execute([$appareil_global_id]);

$stmtInsert = $pdo->prepare("
    INSERT INTO regles_parentales(appareil_id, jour, heure_debut, heure_fin, actif)
    VALUES(?, ?, ?, ?, ?)
");

foreach($jours as $jour){
    $actif = isset($_POST['actif'][$jour]) ? 1 : 0;
    $heure_debut = !empty($_POST['heure_debut'][$jour]) ? $_POST['heure_debut'][$jour] : '00:00';
    $heure_fin = !empty($_POST['heure_fin'][$jour]) ? $_POST['heure_fin'][$jour] : '00:00';

    $stmtInsert->execute([
        $appareil_global_id,
        $jour,
        $heure_debut,
        $heure_fin,
        $actif
    ]);
}

header("Location: gestion_restrictions.php");
exit;
