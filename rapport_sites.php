<?php
$rapportFile = "/var/www/html/rapport_sites.csv";
$lignes = [];

if(file_exists($rapportFile)){
    $contenu = file($rapportFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($contenu as $index => $ligne){
        if($index === 0){
            continue;
        }
        $parties = explode(';', $ligne);
        if(count($parties) === 4){
            $lignes[] = $parties;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport journalier des sites</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:20px;}
        .container{max-width:1100px;margin:auto;}
        .box{background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
        h1{color:#1f2d3d;}
        table{width:100%;border-collapse:collapse;margin-top:20px;}
        th,td{border:1px solid #ddd;padding:10px;text-align:left;}
        th{background:#2563eb;color:#fff;}
        tr:nth-child(even){background:#f9fafb;}
        .vide{color:#777;font-style:italic;}
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <h1>Rapport journalier des sites les plus consommateurs</h1>

        <?php if(count($lignes) > 0): ?>
            <table>
                <tr>
                    <th>Site</th>
                    <th>Nb connexions</th>
                    <th>Débit total (Mbps)</th>
                    <th>Débit moyen (Mbps)</th>
                </tr>
                <?php foreach($lignes as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne[0]) ?></td>
                        <td><?= htmlspecialchars($ligne[1]) ?></td>
                        <td><?= htmlspecialchars($ligne[2]) ?></td>
                        <td><?= htmlspecialchars($ligne[3]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="vide">Aucune donnée de rapport disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
