<?php
$pdo = new PDO("mysql:host=localhost;dbname=ams;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(
    !empty($_POST['appareil_id']) &&
    !empty($_POST['jour']) &&
    !empty($_POST['heure_debut']) &&
    !empty($_POST['heure_fin'])
){
    $stmt = $pdo->prepare("INSERT INTO regles_parentales(appareil_id, jour, heure_debut, heure_fin, actif) VALUES(?, ?, ?, ?, 1)");
    $stmt->execute([
        $_POST['appareil_id'],
        $_POST['jour'],
        $_POST['heure_debut'],
        $_POST['heure_fin']
    ]);
}

header("Location: controle_parental.php");
exit;
