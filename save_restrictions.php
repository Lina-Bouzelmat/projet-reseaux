<?php
require_once("db_natbox.php");

if(empty($_POST['appareil_id'])){
    header("Location: gestion_restrictions.php");
    exit;
}

$appareil_id = (int)$_POST['appareil_id'];

$stmtDelete = $pdo->prepare("DELETE FROM grille_horaire WHERE appareil_id = ?");
$stmtDelete->execute([$appareil_id]);

$stmtInsert = $pdo->prepare("
    INSERT INTO grille_horaire(appareil_id, jour, heure, bloque)
    VALUES(?, ?, ?, ?)
");

if(isset($_POST['grille']) && is_array($_POST['grille'])){
    foreach($_POST['grille'] as $jour => $heures){
        foreach($heures as $heure => $bloque){
            $stmtInsert->execute([
                $appareil_id,
                $jour,
                (int)$heure,
                (int)$bloque
            ]);
        }
    }
}

header("Location: gestion_restrictions.php?appareil_id=".$appareil_id);
exit;
