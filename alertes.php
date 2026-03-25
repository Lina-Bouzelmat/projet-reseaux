<?php
$fichier = "/var/www/html/logs_connexions.csv";
$lignes = [];

if(file_exists($fichier)){
    $contenu = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($contenu as $index => $ligne){
        if($index === 0) continue;
        $lignes[] = explode(";", $ligne);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alertes NATBOX</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;}
        .box{background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
        table{width:100%;border-collapse:collapse;}
        th,td{border:1px solid #ddd;padding:10px;text-align:left;}
        th{background:#2563eb;color:#fff;}
        .alerte{color:#b91c1c;font-weight:bold;}
        a{display:inline-block;margin-bottom:20px;text-decoration:none;background:#111827;color:white;padding:10px 14px;border-radius:8px;}
    </style>
</head>
<body>
    <a href="index.php">Accueil</a>
    <div class="box">
        <h1>Alertes et logs NATBOX</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Appareil</th>
                <th>IP</th>
                <th>MAC</th>
                <th>Jour</th>
                <th>Heure</th>
                <th>Etat</th>
                <th>Débit</th>
                <th>Alerte</th>
            </tr>
            <?php foreach($lignes as $ligne): ?>
                <tr>
                    <td><?= htmlspecialchars($ligne[0]) ?></td>
                    <td><?= htmlspecialchars($ligne[1]) ?></td>
                    <td><?= htmlspecialchars($ligne[2]) ?></td>
                    <td><?= htmlspecialchars($ligne[3]) ?></td>
                    <td><?= htmlspecialchars($ligne[4]) ?></td>
                    <td><?= htmlspecialchars($ligne[5]) ?>h</td>
                    <td><?= htmlspecialchars($ligne[6]) ?></td>
                    <td><?= htmlspecialchars($ligne[7]) ?> Mbps</td>
                    <td class="alerte"><?= htmlspecialchars($ligne[8]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
