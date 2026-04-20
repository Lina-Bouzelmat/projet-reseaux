<?php
require_once("db_natbox.php");

if(empty($_GET['appareil_id'])){
    die("Appareil manquant.");
}

$appareil_id = (int)$_GET['appareil_id'];

$stmtAppareil = $pdo->prepare("SELECT * FROM appareils WHERE id = ?");
$stmtAppareil->execute([$appareil_id]);
$appareil = $stmtAppareil->fetch(PDO::FETCH_ASSOC);

if(!$appareil){
    die("Appareil introuvable.");
}

$stmt = $pdo->prepare("
    SELECT s.domaine
    FROM blocages_sites b
    JOIN sites_catalogue s ON b.site_id = s.id
    WHERE b.appareil_id = ?
    AND b.actif = 1
    ORDER BY s.domaine ASC
");
$stmt->execute([$appareil_id]);
$sites = $stmt->fetchAll(PDO::FETCH_COLUMN);

$contenu = "";

foreach($sites as $domaine){
    $contenu .= "zone \"$domaine\" {\n";
    $contenu .= "    type master;\n";
    $contenu .= "    file \"/etc/bind/blocked/db.blocked\";\n";
    $contenu .= "};\n\n";

    $contenu .= "zone \"www.$domaine\" {\n";
    $contenu .= "    type master;\n";
    $contenu .= "    file \"/etc/bind/blocked/db.blocked\";\n";
    $contenu .= "};\n\n";
}

file_put_contents("/tmp/named.conf.blocked", $contenu);

header("Content-Type: text/plain; charset=UTF-8");
echo "Appareil : ".($appareil['nom'] ?: 'Appareil')."\n";
echo "IP : ".$appareil['ip']."\n";
echo "MAC : ".$appareil['mac']."\n\n";
echo "Fichier généré : /tmp/named.conf.blocked\n\n";
echo $contenu;
