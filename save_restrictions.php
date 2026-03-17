<?php
require_once("db_natbox.php");

$appareilGlobal = $pdo->query("SELECT id FROM appareils WHERE nom='TOUS_LES_APPAREILS' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if(!$appareilGlobal){
    die("Appareil global introuvable.");
}

$appareil_global_id = (int)$appareilGlobal['id'];

$stmtDelete = $pdo->prepare("DELETE FROM grille_horaire WHERE appareil_id = ?");
$stmtDelete->execute([$appareil_global_id]);

$stmtInsert = $pdo->prepare("
    INSERT INTO grille_horaire(appareil_id, jour, heure, bloque)
    VALUES(?, ?, ?, ?)
");

if(isset($_POST['grille']) && is_array($_POST['grille'])){
    foreach($_POST['grille'] as $jour => $heures){
        foreach($heures as $heure => $bloque){
            $stmtInsert->execute([
                $appareil_global_id,
                $jour,
                (int)$heure,
                (int)$bloque
            ]);
        }
    }
}

header("Location: gestion_restrictions.php");
exit;
