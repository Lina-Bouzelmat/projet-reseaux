<?php
require_once("db_natbox.php");

$appareils = [];
$grille = [];
$appareil_id = 0;
$appareilSelectionne = null;

$jours = [
    "lundi" => "Lundi",
    "mardi" => "Mardi",
    "mercredi" => "Mercredi",
    "jeudi" => "Jeudi",
    "vendredi" => "Vendredi",
    "samedi" => "Samedi",
    "dimanche" => "Dimanche"
];

try{
    $stmtAppareils = $pdo->query("
        SELECT *
        FROM appareils
        WHERE mac <> '00:00:00:00:00:00'
        ORDER BY nom ASC, ip ASC
    ");
    $appareils = $stmtAppareils->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_GET['appareil_id']) && !empty($_GET['appareil_id'])){
        $appareil_id = (int)$_GET['appareil_id'];
    } elseif(count($appareils) > 0){
        $appareil_id = (int)$appareils[0]['id'];
    }

    foreach($appareils as $appareil){
        if((int)$appareil['id'] === $appareil_id){
            $appareilSelectionne = $appareil;
            break;
        }
    }

    foreach(array_keys($jours) as $jour){
        for($h = 0; $h < 24; $h++){
            $grille[$jour][$h] = 1;
        }
    }

    if($appareil_id > 0){
        $stmtGrille = $pdo->prepare("
            SELECT jour, heure, bloque
            FROM grille_horaires
            WHERE appareil_id = ?
        ");
        $stmtGrille->execute([$appareil_id]);
        $resultats = $stmtGrille->fetchAll(PDO::FETCH_ASSOC);

        foreach($resultats as $ligne){
            $jour = $ligne['jour'];
            $heure = (int)$ligne['heure'];
            $bloque = (int)$ligne['bloque'];

            if(isset($grille[$jour][$heure])){
                $grille[$jour][$heure] = $bloque == 1 ? 0 : 1;
            }
        }
    }

} catch(PDOException $e){
    die("Erreur lors du chargement des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrôle parental - LinaFAI</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .subtitle{
            color:#cccccc;
            line-height:1.6;
            margin-bottom:20px;
        }

        .legend-box{
            display:flex;
            gap:20px;
            flex-wrap:wrap;
            margin:20px 0;
        }

        .legend-item{
            display:flex;
            align-items:center;
            gap:8px;
            color:#fff;
        }

        .legend-color{
            width:16px;
            height:16px;
            border-radius:4px;
            display:inline-block;
        }

        .legend-green{
            background:#2fbf73;
        }

        .legend-red{
            background:#ef4444;
        }

        .grid-wrapper{
            overflow-x:auto;
            margin-top:20px;
            border:1px solid #2a2a2a;
            border-radius:12px;
            background:#111;
        }

        .grid-table{
            width:100%;
            min-width:1100px;
            border-collapse:collapse;
            margin-top:0;
        }

        .grid-table th{
            background:#2563eb;
            color:#fff;
            padding:10px;
            border:1px solid #2a2a2a;
            font-size:13px;
            text-align:center;
        }

        .grid-table td{
            padding:8px;
            border:1px solid #2a2a2a;
            text-align:center;
            background:#0f0f0f;
        }

        .grid-table td:first-child{
            text-align:left;
            font-weight:bold;
            white-space:nowrap;
            background:#141414;
            color:#fff;
        }

        .slot{
            width:22px;
            height:22px;
            border:none;
            border-radius:5px;
            cursor:pointer;
            display:inline-block;
            margin:0;
            padding:0;
        }

        .slot.allowed{
            background:#2fbf73;
        }

        .slot.blocked{
            background:#ef4444;
        }

        .actions-row{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:20px;
        }

        .actions-row button{
            width:auto;
        }

        .table-card{
            margin-top:25px;
        }

        .config-box{
            background:#111;
            border:1px solid #2a2a2a;
            border-radius:10px;
            padding:14px;
            margin-top:18px;
            color:#fff;
        }

        .config-box strong{
            color:#3ddc84;
        }

        .select-inline{
            max-width:420px;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <div class="card">
        <h1>Contrôle parental par appareil</h1>
        <p class="subtitle">
            Choisis un appareil, puis clique sur les cases horaires.
            Vert = autorisé, rouge = bloqué.
        </p>

        <form method="get">
            <label for="appareil_id">Appareil à configurer :</label>
            <select name="appareil_id" id="appareil_id" class="select-inline" onchange="this.form.submit()">
                <?php foreach($appareils as $appareil): ?>
                    <option value="<?= (int)$appareil['id'] ?>" <?= ((int)$appareil['id'] === $appareil_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(($appareil['nom'] ?: 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if($appareilSelectionne): ?>
            <div class="config-box">
                <strong>Configuration actuelle :</strong>
                <?= htmlspecialchars(($appareilSelectionne['nom'] ?: 'Appareil').' - '.$appareilSelectionne['ip'].' - '.$appareilSelectionne['mac']) ?>
            </div>
        <?php endif; ?>

        <div class="legend-box">
            <div class="legend-item">
                <span class="legend-color legend-green"></span>
                <span>Autorisé</span>
            </div>
            <div class="legend-item">
                <span class="legend-color legend-red"></span>
                <span>Bloqué</span>
            </div>
        </div>

        <form action="save_restrictions.php" method="post">
            <input type="hidden" name="appareil_id" value="<?= (int)$appareil_id ?>">

            <div class="grid-wrapper">
                <table class="grid-table">
                    <tr>
                        <th>Jour</th>
                        <?php for($h = 0; $h < 24; $h++): ?>
                            <th><?= $h ?></th>
                        <?php endfor; ?>
                    </tr>

                    <?php foreach($jours as $jourCle => $jourLabel): ?>
                        <tr>
                            <td><?= htmlspecialchars($jourLabel) ?></td>

                            <?php for($h = 0; $h < 24; $h++): 
                                $etat = isset($grille[$jourCle][$h]) ? (int)$grille[$jourCle][$h] : 1;
                            ?>
                                <td>
                                    <input
                                        type="hidden"
                                        name="cases[<?= htmlspecialchars($jourCle) ?>][<?= $h ?>]"
                                        value="<?= $etat ?>"
                                        class="state-input"
                                    >

                                    <button
                                        type="button"
                                        class="slot <?= $etat == 1 ? 'allowed' : 'blocked' ?>"
                                        onclick="toggleSlot(this)">
                                    </button>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="actions-row">
                <button type="submit">Enregistrer la grille</button>
                <button type="button" onclick="setAllSlots(1)">Tout autoriser</button>
                <button type="button" onclick="setAllSlots(0)">Tout bloquer</button>
            </div>
        </form>
    </div>

    <div class="card table-card">
        <h2>Appareils détectés sur la box</h2>

        <?php if(count($appareils) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>IP</th>
                    <th>MAC</th>
                </tr>

                <?php foreach($appareils as $appareil): ?>
                    <tr>
                        <td><?= (int)$appareil['id'] ?></td>
                        <td><?= htmlspecialchars($appareil['nom'] ?: 'Appareil') ?></td>
                        <td><?= htmlspecialchars($appareil['ip']) ?></td>
                        <td><?= htmlspecialchars($appareil['mac']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucun appareil détecté.</p>
        <?php endif; ?>
    </div>

</div>

<div class="footer">
    LinaFAI – Espace de contrôle parental
</div>

<script>
function toggleSlot(button){
    var input = button.parentElement.querySelector('.state-input');
    var current = parseInt(input.value);

    if(current === 1){
        input.value = 0;
        button.classList.remove('allowed');
        button.classList.add('blocked');
    } else {
        input.value = 1;
        button.classList.remove('blocked');
        button.classList.add('allowed');
    }
}

function setAllSlots(state){
    var inputs = document.querySelectorAll('.state-input');
    var buttons = document.querySelectorAll('.slot');

    inputs.forEach(function(input){
        input.value = state;
    });

    buttons.forEach(function(button){
        button.classList.remove('allowed', 'blocked');
        button.classList.add(state === 1 ? 'allowed' : 'blocked');
    });
}
</script>

</body>
</html>
