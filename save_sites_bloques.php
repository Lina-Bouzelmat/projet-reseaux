<?php
require_once("db_natbox.php");

if(empty($_POST['appareil_id'])){
    header("Location: gestion_sites.php");
    exit;
}

$appareil_id = (int)$_POST['appareil_id'];
$sites = isset($_POST['sites']) && is_array($_POST['sites']) ? $_POST['sites'] : [];

$stmtDelete = $pdo->prepare("DELETE FROM blocages_sites WHERE appareil_id = ?");
$stmtDelete->execute([$appareil_id]);

$stmtInsert = $pdo->prepare("
    INSERT INTO blocages_sites(appareil_id, site_id, actif)
    VALUES(?, ?, 1)
");

foreach($sites as $site_id){
    $stmtInsert->execute([$appareil_id, (int)$site_id]);
}

header("Location: gestion_sites.php?appareil_id=".$appareil_id);
exit;
