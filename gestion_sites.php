<?php
require_once("db_natbox.php");

$appareils = $pdo->query("
    SELECT *
    FROM appareils
    WHERE mac <> '00:00:00:00:00:00'
    ORDER BY nom ASC, ip ASC
")->fetchAll(PDO::FETCH_ASSOC);

$appareil_id = isset($_GET['appareil_id']) ? (int)$_GET['appareil_id'] : 0;

if($appareil_id <= 0 && count($appareils) > 0){
    $appareil_id = (int)$appareils[0]['id'];
}

$appareilSelectionne = null;
foreach($appareils as $appareil){
    if((int)$appareil['id'] === $appareil_id){
        $appareilSelectionne = $appareil;
        break;
    }
}

$categories = $pdo->query("
    SELECT c.id AS categorie_id, c.nom AS categorie_nom, s.id AS site_id, s.domaine
    FROM categories_sites c
    LEFT JOIN sites_catalogue s ON s.categorie_id = c.id
    ORDER BY c.nom ASC, s.domaine ASC
")->fetchAll(PDO::FETCH_ASSOC);

$sitesBloques = [];
if($appareil_id > 0){
    $stmt = $pdo->prepare("
        SELECT site_id
        FROM blocages_sites
        WHERE appareil_id = ? AND actif = 1
    ");
    $stmt->execute([$appareil_id]);
    $sitesBloques = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$groupes = [];
foreach($categories as $ligne){
    $catId = $ligne['categorie_id'];
    if(!isset($groupes[$catId])){
        $groupes[$catId] = [
            'nom' => $ligne['categorie_nom'],
            'sites' => []
        ];
    }

    if(!empty($ligne['site_id'])){
        $groupes[$catId]['sites'][] = [
            'id' => $ligne['site_id'],
            'domaine' => $ligne['domaine']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des sites bloqués – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1>Gestion des sites bloqués par appareil</h1>

    <div class="card">
        <p>Choisis un appareil, puis coche les sites à bloquer.</p>

        <form method="get">
            <label>Appareil à configurer :</label>
            <select name="appareil_id" onchange="this.form.submit()">
                <?php foreach($appareils as $appareil): ?>
                    <option value="<?= (int)$appareil['id'] ?>" <?= ((int)$appareil['id'] === $appareil_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(($appareil['nom'] ?: 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if($appareilSelectionne): ?>
            <p><strong>Configuration actuelle :</strong> <?= htmlspecialchars(($appareilSelectionne['nom'] ?: 'Appareil').' - '.$appareilSelectionne['ip'].' - '.$appareilSelectionne['mac']) ?></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <form action="save_sites_bloques.php" method="post">
            <input type="hidden" name="appareil_id" value="<?= (int)$appareil_id ?>">

            <?php foreach($groupes as $categorie): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($categorie['nom']) ?></h2>

                    <?php if(count($categorie['sites']) > 0): ?>
                        <table>
                            <tr>
                                <th>Site</th>
                                <th>Blocage</th>
                            </tr>
                            <?php foreach($categorie['sites'] as $site): ?>
                                <tr>
                                    <td><?= htmlspecialchars($site['domaine']) ?></td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="sites[]"
                                            value="<?= (int)$site['id'] ?>"
                                            <?= in_array($site['id'], $sitesBloques) ? 'checked' : '' ?>
                                            style="width:auto;"
                                        >
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>Aucun site dans cette catégorie.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit">Enregistrer les blocages</button>
        </form>
    </div>

</div>

<div class="footer">
    LinaFAI – Blocage des sites par appareil
</div>

</body>
</html>
