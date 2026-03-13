<?php
$pdo = new PDO("mysql:host=localhost;dbname=ams;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$appareils = $pdo->query("SELECT * FROM appareils ORDER BY nom ASC, ip ASC")->fetchAll(PDO::FETCH_ASSOC);
$regles = $pdo->query("
    SELECT r.*, a.nom, a.mac, a.ip
    FROM regles_parentales r
    JOIN appareils a ON r.appareil_id = a.id
    ORDER BY a.nom, r.jour
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrôle parental NATBOX</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:30px;}
        h1,h2{color:#1f2d3d;}
        .box{background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:25px;}
        label{display:block;margin-top:12px;font-weight:bold;}
        select,input{width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:8px;}
        button{margin-top:18px;background:#2563eb;color:white;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;}
        table{width:100%;border-collapse:collapse;margin-top:15px;}
        th,td{border:1px solid #ddd;padding:10px;text-align:left;}
        th{background:#2563eb;color:white;}
    </style>
</head>
<body>

<div class="box">
    <h1>Contrôle parental NATBOX</h1>
    <form action="save_regle.php" method="post">
        <label>Appareil</label>
        <select name="appareil_id" required>
            <?php foreach($appareils as $appareil): ?>
                <option value="<?= $appareil['id'] ?>">
                    <?= htmlspecialchars(($appareil['nom'] ?: 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Jour</label>
        <select name="jour" required>
            <option value="lundi">Lundi</option>
            <option value="mardi">Mardi</option>
            <option value="mercredi">Mercredi</option>
            <option value="jeudi">Jeudi</option>
            <option value="vendredi">Vendredi</option>
            <option value="samedi">Samedi</option>
            <option value="dimanche">Dimanche</option>
        </select>

        <label>Heure de début de blocage</label>
        <input type="time" name="heure_debut" required>

        <label>Heure de fin de blocage</label>
        <input type="time" name="heure_fin" required>

        <button type="submit">Enregistrer la règle</button>
    </form>
</div>

<div class="box">
    <h2>Règles enregistrées</h2>
    <table>
        <tr>
            <th>Appareil</th>
            <th>IP</th>
            <th>MAC</th>
            <th>Jour</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Actif</th>
        </tr>
        <?php foreach($regles as $regle): ?>
        <tr>
            <td><?= htmlspecialchars($regle['nom'] ?: 'Appareil') ?></td>
            <td><?= htmlspecialchars($regle['ip']) ?></td>
            <td><?= htmlspecialchars($regle['mac']) ?></td>
            <td><?= htmlspecialchars($regle['jour']) ?></td>
            <td><?= htmlspecialchars($regle['heure_debut']) ?></td>
            <td><?= htmlspecialchars($regle['heure_fin']) ?></td>
            <td><?= $regle['actif'] ? 'Oui' : 'Non' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
