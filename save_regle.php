<?php
require_once("db_natbox.php");

if(
    isset($_POST['appareil_id'], $_POST['jour'], $_POST['heure_debut'], $_POST['heure_fin']) &&
    !empty($_POST['appareil_id']) &&
    !empty($_POST['jour']) &&
    !empty($_POST['heure_debut']) &&
    !empty($_POST['heure_fin'])
){
    $appareil_id = $_POST['appareil_id'];
    $jour = $_POST['jour'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    $stmt = $pdo->prepare("
        INSERT INTO regles_parentales(appareil_id, jour, heure_debut, heure_fin, actif)
        VALUES(?, ?, ?, ?, 1)
    ");
    $stmt->execute([$appareil_id, $jour, $heure_debut, $heure_fin]);
}

header("Location: controle_parental.php");
exit;
