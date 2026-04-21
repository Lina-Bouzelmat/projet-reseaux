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
<title>Rapport journalier – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1>Rapport journalier des sites les plus consommateurs</h1>

    <div class="card">
        <?php if(count($lignes) > 0): ?>
            <table>
                <tr>
                    <th>Site</th>
                    <th>Nb connexions</th>
                    <th>Débit total estimé (Mbps)</th>
                    <th>Débit moyen estimé (Mbps)</th>
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
            <p>Aucune donnée disponible pour le moment.</p>
        <?php endif; ?>
    </div>

</div>

<div class="footer">
    LinaFAI – Rapport journalier
</div>

</body>
</html>
