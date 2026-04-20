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
    <title>Gestion des sites bloqués</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:20px;}
        .container{max-width:1200px;margin:auto;}
        .box{background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:25px;}
        h1,h2,h3{margin-top:0;color:#1f2d3d;}
        .btn{display:inline-block;background:#111827;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none;margin-right:10px;}
        .btn:hover{background:#000;}
        select{padding:10px;border:1px solid #ccc;border-radius:8px;min-width:380px;}
        .top-form{margin:15px 0 20px 0;}
        .categorie{margin-bottom:25px;padding:15px;border:1px solid #e5e7eb;border-radius:10px;background:#fafafa;}
        .site-item{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-bottom:1px solid #eee;}
        .site-item:last-child{border-bottom:none;}
        .actions{margin-top:20px;}
        button{background:#2563eb;color:#fff;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;margin-right:10px;}
        button:hover{background:#1d4ed8;}
        .small{font-size:13px;color:#555;}
        .vide{color:#777;font-style:italic;}
        .checkzone{display:flex;align-items:center;gap:12px;}
    </style>
</head>
<body>
<div class="container">

    <div class="box">
        <a class="btn" href="index.php">Accueil</a>
        <a class="btn" href="menu.php">Menu</a>
        <a class="btn" href="gestion_restrictions.php">Plages horaires</a>
    </div>

    <div class="box">
        <h1>Gestion des sites bloqués par appareil</h1>
        <p class="small">Choisis un appareil, puis coche les sites à bloquer.</p>

        <form method="get" class="top-form">
            <label><strong>Appareil à configurer :</strong></label><br><br>
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

    <div class="box">
        <form action="save_sites_bloques.php" method="post">
            <input type="hidden" name="appareil_id" value="<?= (int)$appareil_id ?>">

            <?php foreach($groupes as $categorie): ?>
                <div class="categorie">
                    <h3><?= htmlspecialchars($categorie['nom']) ?></h3>

                    <?php if(count($categorie['sites']) > 0): ?>
                        <?php foreach($categorie['sites'] as $site): ?>
                            <div class="site-item">
                                <span><?= htmlspecialchars($site['domaine']) ?></span>
                                <label class="checkzone">
                                    <input type="checkbox" name="sites[]" value="<?= (int)$site['id'] ?>"
                                        <?= in_array($site['id'], $sitesBloques) ? 'checked' : '' ?>>
                                    Bloqué
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="vide">Aucun site dans cette catégorie.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="actions">
                <button type="submit">Enregistrer les blocages</button>
            </div>
        </form>
    </div>

</div>
</body>
</html>
